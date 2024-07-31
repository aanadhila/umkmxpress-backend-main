<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class GoogleMapsAPIController extends Controller
{
    private $key;

    public function __construct()
    {
        $this->key = config('app.gmaps_api_key');
    }

    public function getDistance(Request $request, $origin = null, $destination = null)
    {
        try {
            $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?key=' . $this->key . '&origins=' . ($origin ?? $request->origin) . '&destinations=' . ($destination ?? $request->destination);
            return Http::get($url)->getBody();
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getCourier(Request $request)
    {
        $cluster = $request->cluster;

        // $courier = Shipment::query()
        // ->select('courier_id', DB::raw('count(*) as total'))
        // ->where(['new_cluster' => $cluster, 'status' => 8])
        // ->groupBy('courier_id')
        // ->orderBy('total', 'asc')
        // ->first();
        // shipments_count
        $courier = Courier::query()
            ->where(['new_cluster' => $cluster])
            ->withCount(['shipments' => function ($query) {
                $query->where('status', 8);
            }])
            ->orderBy('shipments_count', 'asc')
            ->first();

        if ($courier) {
            $courier_id = $courier->id;
            $data_courier = Courier::query()
                ->with('user')
                ->findOrFail($courier_id);

            return response()->json([
                'courier_id' => $courier_id,
                'courier_name' => $data_courier->user->name
            ]);
        } else {
        }
    }
}
