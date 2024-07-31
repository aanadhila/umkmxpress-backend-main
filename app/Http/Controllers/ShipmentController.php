<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use App\Models\Courier;
use App\Models\Expedition;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Recipient;
use App\Models\Sender;
use App\Models\Shipment;
use App\Models\SpecialCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ShipmentController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $shipment = Shipment::when(request()->status, function ($query) {
                return $query->where('status', request()->status);
            })->when(request()->subdistrict_id, function ($query) {
                return $query->whereRelation('sender', 'subdistrict_id', request()->subdistrict_id)
                    ->orWhereRelation('recipient', 'subdistrict_id', request()->subdistrict_id);
            })->orderBy('updated_at', 'desc')->get();
            return DataTables::of($shipment)
                ->addColumn('pengirim', function ($item) {
                    if ($item->sender) {
                        return '
                            <p>
                                <b>' . $item->sender->name . '</b>
                                <br>
                                ' . $item->sender->phonenumber . '
                                <br>
                                ' . $item->sender->full_address . '
                            </p>
                            ';
                    } else if ($item->user) {
                        return '
                            <p>
                                <b>' . $item->user->name . '</b>
                                <br>
                                ' . $item->user->phonenumber . '
                                <br>
                                ' . $item->sender->full_address . '
                            </p>
                            ';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('penerima', function ($item) {
                    if ($item->recipient) {
                        return '
                            <p>
                                <b>' . $item->recipient->name . '</b>
                                <br>
                                ' . $item->recipient->phonenumber . '
                                <br>
                                ' . $item->recipient->full_address . '
                            </p>
                            ';
                    } else {
                        return '-';
                    }
                })
                ->editColumn('distance', function ($item) {
                    if ($item->expedition) {
                        return $item->distance . ' Km';
                    } else if ($item->cluster) {
                        return $item->cluster->name;
                    } else if ($item->special_cost) {
                        return $item->special_cost->name;
                    } else if($item->new_cluster){
                        return $item->distance . " Km / " . $item->new_cluster;
                    }
                })
                ->editColumn('total_price', function ($item) {
                    if ($item->expedition) {
                        return '
                            <p>
                                <b>Rp ' . number_format(intval($item->total_price), 0, ',', '.') . '</b>
                                <br>
                                ' . $item->expedition->name . '
                            </p>
                            ';
                    } else if ($item->cluster) {
                        if ($item->cost_type == 'next_day') {
                            return '<p><b>Rp ' . number_format(intval($item->cluster->next_day_cost), 0, ',', '.') . '</b><br>Same Day</p>';
                        } else {
                            return '<p><b>Rp ' . number_format(intval($item->cluster->instant_courier_cost), 0, ',', '.') . '</b><br>Instant Courier</p>';
                        }
                    } else if ($item->special_cost) {
                        return $item->special_cost->cost;
                    } else if($item->new_cluster){
                        if ($item->cost_type == 'next_day') {
                            return '<p><b>Rp ' . number_format(intval($item->new_sameday_price), 0, ',', '.') . '</b><br>Same Day</p>';
                        } else {
                            return '<p><b>Rp ' . number_format(intval($item->new_instant_price), 0, ',', '.') . '</b><br>Instant Courier</p>';
                        }
                    }
                })
                ->addColumn('badge', function ($item) {
                    return config('data.shipment_status')[$item->status]['badge'];
                })
                ->addColumn('actions', function ($item) {
                    return '<div class="dropdown">
                                <button class="btn btn-light btn-active-light-primary btn-sm" type="button" id="menu-' . $item->id . '" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                    <span class="svg-icon svg-icon-5 m-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                </button>
                                <div class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-50px py-4" aria-labelledby="menu-' . $item->id . '" data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <a class="menu-link px-3" href="' . route('shipments.show', $item->id) . '">Rincian</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a class="menu-link px-3" id="updateStatusShipment" data-bs-toggle="modal" data-bs-target="#updateStatusShipmentModal" data-id="' . $item->id . '">Update Status</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a class="menu-link px-3" id="showItems" data-bs-toggle="modal" data-bs-target="#shipmentItemsModal" data-shipment_id="' . $item->id . '" data-airway_bill="' . $item->airway_bill . '">Data Barang</a>
                                    </div>
                                </div>
                            </div>';
                })
                ->AddIndexColumn()
                ->rawColumns(['pengirim', 'penerima', 'badge', 'total_price', 'actions'])
                ->make();
        }
        return view('admin.shipment.index');
    }

    public function create(Request $request)
    {

        if ($request->ajax()) {
            try {
                if($request->lat && $request->lon){
                    $data = Http::post('http://127.0.0.1:5000/', [
                        'lat' => $request->lat,
                        'lon' => $request->lon
                    ])->body();
    
                    $element = json_decode($data);
    
                    $cluster = $element->cluster;
                    $instant = $element->instant;
                    $sameday = $element->sameday;
    
                    return response()->json([
                        'cluster' => 'Kluster ' . $cluster,
                        'instant' => $instant,
                        'sameday' => $sameday
                    ], 200);
                } else if ($request->getSender) {
                    $sender = Sender::find($request->id);
                    $sender->address = $sender->name . ', ' . $sender->full_address . ', ' . $sender->phonenumber;
                    return response()->json($sender, 200);
                } else if ($request->getRecipient) {
                    $recipient = Recipient::find($request->id);
                    $recipient->address = $recipient->name . ', ' . $recipient->full_address . ', ' . $recipient->phonenumber;
                    return response()->json($recipient, 200);
                } else if ($request->getPrice) {
                    $expedition = Expedition::find($request->id);
                    return response()->json($expedition->price ?? $expedition->price_km, 200);
                } else if ($request->getCost) {
                    $cluster = Cluster::whereRelation('coverages', 'subdistrict_id', $request->senderSubdistrictId)
                        ->whereRelation('coverages', 'subdistrict_id', $request->recipientSubdistrictId)
                        ->first();

                    $specialCost = SpecialCost::with(['origin', 'destination'])->where('origin_subdistrict_id', $request->senderSubdistrictId)
                        ->where('destination_subdistrict_id', $request->recipientSubdistrictId)
                        ->first();

                    return response()->json(['cluster' => $cluster, 'specialCost' => $specialCost], 200);
                } else if ($request->getPaymentCost) {
                    $paymentMethod = PaymentMethod::find($request->id);
                    return response()->json($paymentMethod, 200);
                }
            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage()], 500);
            }
        }

        return view('admin.shipment.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'sender_id'         => 'required',
                'recipient_id'      => 'required',
                // 'expedition_id'     => 'required',
                // 'courier_id'        => 'required',
                // 'time'              => 'required',
                'length'            => 'required',
                'width'             => 'required',
                'height'            => 'required',
                'dimension_weight'  => 'required',
                'total_price'       => 'required',
                'distance'          => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['messages' => $validator->errors()], 500);
            }

            $paymentMethod = PaymentMethod::find($request->payment_method_id);
            $payment = Payment::create([
                'payment_method_id' => $request->payment_method_id,
                'payment_link'      => null,
                'total_payment'     => $request->total_price,
                'status'            => 1,
                'status_updated_at' => now()
            ]);

            if ($request->distance >= 25) return response()->json(['message' => 'Jangkauan jarak terlalu jauh, tidak ada kurir tersedia untuk alamat yang diberikan!'], 500);

            $courier = Courier::withCount('shipments')
                ->when($request->expedition_id, function ($query) use ($request) {
                    return $query->whereHas('cluster', function ($query) use ($request) {
                        $query->whereHas('coverages', function ($query) use ($request) {
                            $query->whereHas('subdistrict', function ($query) use ($request) {
                                $senderCityId = Sender::find($request->sender_id)->subdistrict->city->id;
                                $query->where('city_id', $senderCityId);
                            });
                        });
                    });
                })
                ->when($request->cluster_id, function ($query) use ($request) {
                    $query->where('cluster_id', $request->cluster_id);
                })
                ->when($request->special_cost_id, function ($query) use ($request) {
                    $origin = SpecialCost::find($request->special_cost_id)->origin_sudistrict_id;
                    $query->whereRelation('cluster', 'coverages', 'subdistrict_id', $origin);
                })
                ->orderBy('shipments_count')->first();

            if (!$courier) return response()->json(['message' => 'Tidak ada kurir tersedia untuk alamat yang diberikan!'], 500);

            $user = Sender::find($request->sender_id)->user;
            $shipment = Shipment::create([
                'airway_bill'       => 'AE' . random_int(0, 1000),
                'platform'          => 'ADS Express',
                'user_id'           => $user ? $user->id : null,
                'sender_id'         => $request->sender_id,
                'recipient_id'      => $request->recipient_id,
                'expedition_id'     => $request->expedition_id ?? null,
                'cluster_id'        => $request->cluster_id ?? null,
                'specialCost'       => $request->special_cost_id ?? null,
                'cost_type'         => $request->cost_type ?? null,
                'payment_id'        => $payment->id,
                'courier_id'        => $request->courier_id,
                'distance'          => $request->distance,
                'shipping_time'     => $request->time ?? now(),
                'length'            => $request->length,
                'width'             => $request->width,
                'height'            => $request->height,
                'dimension_weight'  => $request->dimension_weight,
                'total_price'       => $request->total_price,
                'status'            => 1,
                'new_cluster'       => $request->new_cluster,
                'new_instant_price' => $request->new_instant_price,
                'new_sameday_price' => $request->new_sameday_price
            ]);

            foreach ($request->items as $item) {
                $shipment->items()->create([
                    'name'      => $item['name'],
                    'amount'    => $item['amount'],
                    'weight'    => $item['weight'],
                ]);
            }

            $shipment->trackers()->create([
                'note'      => config('data.shipment_status')[1]['note'],
                'status'    => 1,
            ]);

            DB::commit();
            Session::flash('success', 'Pengiriman berhasil dibuat, silahkan meunggu untuk proses pickup oleh driver');
            return response()->json(['url' => route('shipments.index')], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function show(Request $request, $id)
    {
        if ($request->ajax()) {
            try {
                $shipment = Shipment::find($id);
                $shipment->badge = config('data.shipment_status')[$shipment->status]['badge'];
                return response()->json($shipment, 200);
            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage()], 500);
            }
        } else {
            $shipment = Shipment::with(['courier', 'sender', 'recipient', 'user', 'expedition', 'payment', 'items', 'trackers', 'cluster', 'special_cost'])->find($id);
            return view('admin.shipment.details', ['shipment' => $shipment]);
        }
    }

    public function edit(Shipment $shipment)
    {
        //
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $shipment = Shipment::find($id);
            if ($request->status) {
                $shipment->update([
                    'status' => $request->status,
                ]);
                if ($request->status == 6) {
                    $shipment->payment()->update([
                        'status' => 3,
                    ]);
                }
                DB::commit();
                return response()->json(['message' => 'Status berhasil diupdate!'], 200);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function destroy(Shipment $shipment)
    {
        //
    }
}
