<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Shipment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestingCourierController extends Controller
{
    public function index()
{
    $today = Carbon::today();
    $tomorrow = Carbon::tomorrow();

    $assignments = Assignment::with(['shipment', 'shipment.recipient', 'shipment.sender', 'shipment.items', 'courier'])
        ->where('courier_id', auth()->user()->courier->id)
        ->whereBetween('created_at', [$today, $tomorrow])
        ->orderBy('created_at', 'asc')
        ->get();
        

    return view('courier.orders.pickup', ['assignments' => $assignments]);
}

    public function pickup(Request $request)
    {
        DB::beginTransaction();
        $shipment = Shipment::where('id', $request->shipment_id)->first();

        // Flow kurir melakukan pickup shipment
        if ($shipment->status == 1) {
            $courierBalance = $shipment->courier->wallet->balance;
            $totalPrice = $shipment->total_price - $shipment->payment->method->cost;
            if ($courierBalance < $totalPrice) return back()->with('error', 'Saldo anda tidak mencukupi, silahkan lakukan top up saldo terlebih dahulu!');
            $shipment->status = 2;
            $shipment->save();
            $shipment->courier->wallet()->update([
                'balance'   => $shipment->courier->wallet->balance - $totalPrice
            ]);
            $shipment->courier->wallet->transaction()->create([
                'type'      => 4,
                'amount'    => $totalPrice,
                'status'    => 2,
                'note'      => config('data.transaction_type')[4] . ' ' . $shipment->airway_bill,
            ]);
            $shipment->trackers()->create([
                'note'      => config('data.shipment_status')[2]['note'],
                'status'    => 2,
            ]);
            DB::commit();
            return to_route('testing.orders.index')->with('warning', 'Segera menuju lokasi pickup!');
        }

        // Flow kurir tiba di lokasi pickup dan mengambil barang
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
            $courier = $shipment->courier;
            if ($courier->shipments()->where('status', 2)->count() > 0) {
                DB::commit();
                return to_route('testing.orders.index')->with('warning', 'Pesanan berhasil dipickup!, Segera pickup pesanan lain!');
            } else {
                DB::commit();
                return to_route('testing.orders.index')->with('success', 'Semua pesanan telah dipickup, silahkan tunggu hingga waktu pengantaran!');
            }
        }

        // Flow kurir mengantarkan pesanan
        else if ($shipment->status == 3) {
            $shipment->status = 4;
            $shipment->save();
            $shipment->trackers()->create([
                'note'      => config('data.shipment_status')[4]['note'],
                'status'    => 4,
            ]);
            $courier = $shipment->courier;
            if ($courier->shipments()->where('status', 3)->count() > 0) {
                DB::commit();
                return to_route('testing.orders.index')->with('warning', 'Pesanan dalam proses diantarkan, segera proses pesanan lain!');
            } else {
                DB::commit();
                return to_route('testing.orders.index')->with('success', 'Semua pesanan dalam proses pengantaran, segera lakukan proses pengantaran!');
            }
        }

        // Flow kurir tiba di tempat pengantaran
        else if ($shipment->status == 4) {
            $request->validate(['delivered_photo' => 'required|mimes:png,jpg'], ['delivered_photo.required' => 'Upload foto barang terkirim terlebih dahulu!']);
            $photo      = $request->file('delivered_photo');
            if ($photo) {
                $photo_name = $shipment->airway_bill . '_delivered_' . $photo->getClientOriginalExtension();
                $photo_path = $photo->storeAs('shipment', $photo_name, 'public');
            }
            $shipment->delivered_photo = $photo_path;
            $shipment->status = 8;
            $shipment->save();
            $shipment->trackers()->create([
                'note'      => config('data.shipment_status')[5]['note'],
                'status'    => 8,
            ]);
            $courier = $shipment->courier;
            if ($courier->shipments()->where('status', 4)->count() > 0) {
                DB::commit();
                return to_route('testing.orders.index')->with('warning', 'Pesanan berhasil diantar, selesaikan pembayaran!');
            } else {
                DB::commit();
                return to_route('testing.orders.index')->with('success', 'Semua pesanan telah diantarkan!');
            }
        }

        // Flow penyelesaian pembayaran
        else if ($shipment->status == 8) {
            $shipment->payment()->update([
                'status' => 2,
            ]);
            $shipment->payment->histories()->create([
                'note'      => 'Pembayaran pengiriman ' . $shipment->airway_bill,
                'credit'    => 0,
                'debit'     => $shipment->payment->total_payment,
            ]);
            $courier = $shipment->courier;
            if ($courier->shipments()->where('status', 4)->count() > 0) {
                DB::commit();
                return to_route('testing.orders.index')->with('warning', 'Pesanan berhasil diselesaikan, segera antarkan pesanan lain!');
            } else {
                DB::commit();
                return to_route('testing.orders.index')->with('success', 'Semua pesanan telah diselesaikan!');
            }
        }
    }
}
