<?php

namespace App\Http\Controllers\API;

use App\Models\Shipment;
use Illuminate\Http\Request;
use App\Models\CourierWallet;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ShipmentResource;
use App\Models\CourierWalletTransaction;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\ApiController;
use App\Http\Resources\ShipmentPriceResource;
use App\Models\Courier;
use App\Models\Payment;
use App\Models\Recipient;
use App\Models\Sender;
use App\Models\SpecialCost;
use Illuminate\Support\Facades\Http;

class ShipmentController extends ApiController
{
    public function index(Request $request)
    {
        try {
            $shipments = Shipment::where('courier_id', auth()->user()->courier->id)
                ->when($request->status, function ($query) use ($request) {
                    return $query->where('status', $request->status);
                })->get();
            return $this->respondSuccess('success', ShipmentResource::collection($shipments));
        } catch (\Throwable $th) {
            return $this->respondInternalError($th->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
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
                // 'distance'          => 'required',
            ]);

            if ($validator->fails()) {
                return $this->respondInternalError($validator->errors());
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

            $origin = Sender::find($request->sender_id);
            $origin = $origin->latitude . ',' . $origin->longitude;
            $destination = Recipient::find($request->recipient_id);
            $destination = $destination->latitude . ',' . $destination->longitude;

            $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?key=' . config('app.gmaps_api_key') . '&origins=' . $origin . '&destinations=' . $destination;

            $distance = Http::withHeaders([
                'Accept' => 'application/json',
            ])->get($url);
            if ($distance['status'] != 'OK') return $this->respondInternalError('Jangkauan jarak terlalu jauh, tidak ada kurir tersedia untuk alamat yang diberikan!');
            $distance = str_replace(' km', '', $distance['rows'][0]['elements'][0]['distance']['text']);;

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
                'courier_id'        => $courier->id,
                'distance'          => $distance,
                'shipping_time'     => $request->time ?? now(),
                'length'            => $request->length,
                'width'             => $request->width,
                'height'            => $request->height,
                'dimension_weight'  => $request->dimension_weight,
                'total_price'       => $request->total_price,
                'status'            => 1,
            ]);

            if ($request->has('items')){
                foreach ($request->items as $item) {
                    $shipment->items()->create([
                        'name'      => $item['name'],
                        'amount'    => $item['amount'],
                        'weight'    => $item['weight']/1000,
                    ]);
                }
            }

            $shipment->trackers()->create([
                'note'      => config('data.shipment_status')[1]['note'],
                'status'    => 1,
            ]);
            DB::commit();
            return $this->respondSuccess('Berhasil menyimpan data pengiriman');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->respondInternalError($th->getMessage());
        }
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
    public function show(Request $request, $id)
    {
        try {
            $shipments = Shipment::where('courier_id', auth()->user()->courier->id)
                ->when($request->status, function ($query) use ($request) {
                    return $query->where('status', $request->status);
                })->get()->where('id', $id);
            if ($shipments->isEmpty()) {
                return $this->respondNotFound('shipment not found');
            }
            return $this->respondSuccess('success', new ShipmentResource($shipments->first()));
        } catch (\Throwable $th) {
            return $this->respondInternalError($th->getMessage());
        }
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

    public function updateStatus(Request $request, $id)
    {
        DB::beginTransaction();
        $user = auth()->user();
        if (!$user->hasRole('Kurir')) return $this->respondForbidden('Anda bukan kurir');
        $shipments = Shipment::where('courier_id', auth()->user()->courier->id)
            ->when($request->status, function ($query) use ($request) {
                return $query->where('status', $request->status);
            })->get();
        $shipment = $shipments->where('id', $id)->first();
        if ($shipment == null) {
            return $this->respondNotFound('shipment not found');
        }
        // Notif Pickup
        if ($shipment->status == 1) {
            $shipment->status = 2;
            $shipment->save();
            $shipment->trackers()->create([
                'note'      => config('data.shipment_status')[2]['note'],
                'status'    => 2,
            ]);
            DB::commit();
            return $this->respondSuccess('success', 'Notifikasi Pickup!');
        }
        // Pesanan dipickup
        else if ($shipment->status == 2) {
            $request->validate(['pickup_photo' => 'required|mimes:png,jpg'], ['pickup_photo.required' => 'Upload foto pickup terlebih dahulu!']);
            $photo      = $request->file('pickup_photo');
            if ($photo) {
                $photo_name = $shipment->airway_bill . '_pickup_' . $photo->getClientOriginalExtension();
                $photo_path = $photo->storeAs('shipment', $photo_name, 'public');
            }
            $shipment->pickup_photo = $photo_path;
            $shipment->status = 3;
            $shipment->save();
            $shipment->trackers()->create([
                'note'      => config('data.shipment_status')[3]['note'],
                'status'    => 3,
            ]);
            if ($shipments->where('status', 2)->count() > 0) {
                DB::commit();
                return $this->respondSuccess('warning', 'Pesanan berhasil dipickup!, Segera pickup pesanan lain!');
            } else {
                DB::commit();
                return $this->respondSuccess('success', 'Semua pesanan telah dipickup, silahkan menuju lokasi warehouse!');
            }
        }
        // Pesanan sampai di warehouse
        else if ($shipment->status == 3) {
            $shipment->status = 4;
            $shipment->save();
            $shipment->trackers()->create([
                'note'      => config('data.shipment_status')[4]['note'],
                'status'    => 4,
            ]);
            if ($shipments->where('status', 3)->count() > 0) {
                DB::commit();
                return $this->respondSuccess('warning', 'Pesanan sedang diproses di warehouse, segera proses pesanan lain!');
            } else {
                DB::commit();
                return $this->respondSuccess('success', 'Semua pesanan sedang diproses di warehouse, silahkan tunggu hingga waktu pengantaran!');
            }
        }
        // Admin merubah status dari 4 ke 5
        else if ($shipment->status == 4) {
            if ($shipments->where('status', 3)->count() > 0) {
                return $this->respondSuccess('warning', 'Pesanan sedang diproses di warehouse, segera proses pesanan lain!');
            } else {
                return $this->respondSuccess('success', 'Semua pesanan sedang diproses di warehouse, silahkan tunggu hingga waktu pengantaran!');
            }
        }
        // Notif Delivery
        else if ($shipment->status == 5) {
            $shipment->status = 6;
            $shipment->save();
            $shipment->trackers()->create([
                'note'      => config('data.shipment_status')[6]['note'],
                'status'    => 6,
            ]);
            DB::commit();
            return $this->respondSuccess('success', 'Notifikasi berhasil dikirim!');
        }
        // Pesanan sampai di penerima
        else if ($shipment->status == 6) {
            $request->validate(['delivered_photo' => 'required|mimes:png,jpg'], ['delivered_photo.required' => 'Upload foto barang terkirim terlebih dahulu!']);
            $photo      = $request->file('delivered_photo');
            if ($photo) {
                $photo_name = $shipment->airway_bill . '_delivered_' . $photo->getClientOriginalExtension();
                $photo_path = $photo->storeAs('shipment', $photo_name, 'public');
            }
            $shipment->delivered_photo = $photo_path;
            $shipment->status = 7;
            $shipment->save();
            $shipment->trackers()->create([
                'note'      => config('data.shipment_status')[7]['note'],
                'status'    => 7,
            ]);
            if ($shipments->where('status', 6)->count() > 0) {
                DB::commit();
                return $this->respondSuccess('warning', 'Pesanan berhasil diantar, segera kirim pesanan lain!');
            } else {
                DB::commit();
                return $this->respondSuccess('success', 'Semua pesanan telah diantarkan!');
            }
        }
        // Pesanan selesai
        else if ($shipment->status == 7) {
            $request->validate(
                [
                    'recipient_alternative_name' => 'required',
                ],
                [
                    'recipient_alternative_name.required' => 'Nama penerima harus diisi!',
                ]
            );
            $shipment->status = 8;
            $shipment->save();
            $shipment->trackers()->create([
                'note'      => config('data.shipment_status')[8]['note'],
                'status'    => 8,
            ]);
            // $amout = $shipment->total_price * 10 / 100;
            $amout = $shipment->total_price;
            CourierWalletTransaction::create([
                'courier_wallet_id' => $user->courier->wallet->id,
                'type'      => 1,
                'amount'    => $amout,
                'status'    => 2,
                'note'      => config('data.transaction_type')[1] . ' saldo kurir ' . $user->name,
            ]);
            $wallet = CourierWallet::where('id', $user->courier->wallet->id)->first();
            $wallet->update([
                'balance'    => $user->courier->wallet->balance + $amout,
            ]);
            if ($shipments->where('status', 7)->count() > 0) {
                DB::commit();
                return $this->respondSuccess('warning', new ShipmentPriceResource($shipment, 'Pesanan berhasil diselesaikan, segera antarkan pesanan lain!'));
            } else {
                DB::commit();
                return $this->respondSuccess('success', new ShipmentPriceResource($shipment, 'Semua pesanan telah diselesaikan!'));
            }
        }
    }

    public function getImageShipment($id)
    {
        try {
            $shipments = Shipment::where('courier_id', auth()->user()->courier->id)->get()->where('id', $id);
            $shipment = $shipments->first();
            if ($shipment == null) {
                return $this->respondNotFound('shipment not found');
            }
            return $this->respondSuccess('success', $shipment->delivered_pict);
        } catch (\Throwable $th) {
            return $this->respondInternalError($th->getMessage());
        }
    }
}
