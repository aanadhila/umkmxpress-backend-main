<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use App\Models\Sender;
use App\Models\Shipment;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SenderPacketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $couriers = Courier::query()->with('user')->get();
        $senders = Sender::query()->get();

        if ($request->ajax()) {
            $sender = $request->sender_id;
            $date = $request->date;
            $courierCount = $request->courier_count;
            $forceReassign = $request->force_reassign ?? false;

            // Check if assignments already exist for this sender and date
            $existingAssignments = Assignment::whereHas('shipment', function ($query) use ($sender, $date) {
                $query->where('sender_id', $sender)->whereDate('created_at', $date);
            })->exists();

            if ($existingAssignments && !$forceReassign) {
                // If assignments exist and we're not forcing a reassignment, retrieve existing data
                $clusterData = $this->getExistingAssignments($sender, $date);
            } else {
                // If no assignments exist or we're forcing a reassignment, perform clustering
                $clusterData = $this->performClustering($sender, $date, $courierCount);

                // Save or update assignments
                DB::transaction(function () use ($clusterData) {
                    foreach ($clusterData as $cluster) {
                        foreach ($cluster['shipments'] as $shipment) {
                            Assignment::updateOrCreate(
                                ['shipment_id' => $shipment['id']],
                                [
                                    'courier_id' => $cluster['courier']['id'],
                                    'cluster_id' => $cluster['cluster_id'],
                                ]
                            );
                        }
                    }
                });
            }

            return response()->json([
                'clusterData' => $clusterData,
                'existingAssignments' => $existingAssignments
            ]);
        }

        return view('admin.sender-packets.index', compact('senders', 'couriers'));
    }

    private function getExistingAssignments($sender, $date)
    {
        $assignments = Assignment::with(['shipment.recipient', 'shipment.items', 'courier.user'])
            ->whereHas('shipment', function ($query) use ($sender, $date) {
                $query->where('sender_id', $sender)->whereDate('created_at', $date);
            })
            ->get()
            ->groupBy('cluster_id');

        $clusterData = [];
        foreach ($assignments as $clusterId => $clusterAssignments) {
            $courier = $clusterAssignments->first()->courier;
            $shipments = $clusterAssignments->map(function ($assignment) {
                $shipment = $assignment->shipment;
                return [
                    'id' => $shipment->id,
                    'lat' => $shipment->recipient->latitude,
                    'lon' => $shipment->recipient->longitude,
                    'recipient' => $shipment->recipient,
                    'items' => $shipment->items
                ];
            })->toArray();

            $clusterData[] = [
                'cluster_id' => $clusterId,
                'courier' => $courier,
                'shipments' => $shipments
            ];
        }

        return $clusterData;
    }

    private function performClustering($sender, $date, $courierCount)
    {
        $shipments = Shipment::query()
            ->with(['recipient', 'items'])
            ->whereIn('status', [1, 2, 3, 4, 5, 6, 7])  // Include all relevant statuses
            ->where('sender_id', $sender)
            ->whereDate('created_at', $date)
            ->get()
            ->toArray();

        // Prepare data for K-means clustering
        $points = [];
        foreach ($shipments as $index => $shipment) {
            $points[$index] = [
                $shipment['recipient']['latitude'],
                $shipment['recipient']['longitude']
            ];
        }

        // Perform K-means clustering
        $clusters = $this->kMeansClustering($points, $courierCount, 100);

        // Assign shipments to clusters and prepare response data
        $clusterData = [];
        $couriers = Courier::with('user')->get()->toArray();
        foreach ($clusters as $clusterId => $clusterPoints) {
            $courier = $couriers[$clusterId % count($couriers)];

            $clusterShipments = [];
            foreach ($clusterPoints as $index => $point) {
                $shipment = $shipments[$index];
                $clusterShipments[] = [
                    'id' => $shipment['id'],
                    'lat' => $shipment['recipient']['latitude'],
                    'lon' => $shipment['recipient']['longitude'],
                    'recipient' => $shipment['recipient'],
                    'items' => $shipment['items']
                ];
            }

            $clusterData[] = [
                'cluster_id' => $clusterId,
                'courier' => $courier,
                'shipments' => $clusterShipments
            ];
        }

        return $clusterData;
    }

    private function kMeansClustering($points, $k, $maxIterations = 100)
    {
        // Initialize centroids randomly
        $centroids = array_rand($points, $k);
        $centroids = array_map(function ($index) use ($points) {
            return $points[$index];
        }, $centroids);

        for ($i = 0; $i < $maxIterations; $i++) {
            // Assign points to nearest centroid
            $clusters = array_fill(0, $k, []);
            foreach ($points as $index => $point) {
                $nearestCentroid = $this->findNearestCentroid($point, $centroids);
                $clusters[$nearestCentroid][$index] = $point;
            }

            // Recalculate centroids
            $newCentroids = [];
            foreach ($clusters as $cluster) {
                if (!empty($cluster)) {
                    $newCentroids[] = $this->calculateMean($cluster);
                }
            }

            // Check for convergence
            if ($centroids == $newCentroids) {
                break;
            }

            $centroids = $newCentroids;
        }

        return $clusters;
    }

    private function findNearestCentroid($point, $centroids)
    {
        $minDistance = PHP_FLOAT_MAX;
        $nearestCentroid = 0;

        foreach ($centroids as $index => $centroid) {
            $distance = $this->calculateDistance($point, $centroid);
            if ($distance < $minDistance) {
                $minDistance = $distance;
                $nearestCentroid = $index;
            }
        }

        return $nearestCentroid;
    }

    private function calculateDistance($point1, $point2)
    {
        return sqrt(pow($point1[0] - $point2[0], 2) + pow($point1[1] - $point2[1], 2));
    }

    private function calculateMean($points)
    {
        $count = count($points);
        $sum = array_reduce($points, function ($carry, $point) {
            $carry[0] += $point[0];
            $carry[1] += $point[1];
            return $carry;
        }, [0, 0]);

        return [
            $sum[0] / $count,
            $sum[1] / $count
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
