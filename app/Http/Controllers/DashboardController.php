<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use App\Models\Shipment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if ($request->year) {
                $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                $data = DB::table('shipments')
                    ->select(DB::raw('DATE_FORMAT(created_at, "%m") as bulan'), DB::raw('COUNT(*) as jumlah_data'))
                    ->groupBy('bulan')
                    ->get();

                for ($i = 1; $i <= 12; $i++) {
                    $bulan = Carbon::create()->month($i)->format('m');
                    $namaBulan[] = Carbon::create(null, $i, 1)->locale('id')->monthName;
                    $dataBulan = $data->where('bulan', $bulan)->first();
                    $hasilData[] = $dataBulan ? $dataBulan->jumlah_data : 0;
                }
            } else if ($request->month) {
                $bulanIni = Carbon::now()->startOfMonth();
                $tanggalAkhir = Carbon::now()->endOfMonth();

                while ($bulanIni->lte($tanggalAkhir)) {
                    $labels[] = $bulanIni->format('d M');

                    $dataHari = Shipment::whereDate('created_at', $bulanIni->format('Y-m-d'))->count();
                    $hasilData[] = $dataHari;

                    $bulanIni->addDay();
                }
            } else if ($request->week) {
                $mingguIni = Carbon::now()->startOfWeek();
                $tanggalAwal = $mingguIni->format('Y-m-d');

                for ($i = 0; $i <= 6; $i++) {
                    $hari = Carbon::parse($tanggalAwal)->addDays($i);
                    $namaHari = $hari->locale('id')->dayName;
                    $labels[] = $namaHari;

                    $dataHari = Shipment::whereDate('created_at', $hari->format('Y-m-d'))->count();
                    $hasilData[] = $dataHari;
                }
            } else if ($request->dateRange) {
                list($tanggalAwalStr, $tanggalAkhirStr) = explode(" - ", $request->dateRange);
                $tanggalAwal = Carbon::createFromFormat('m/d/Y', $tanggalAwalStr)->format('Y-m-d');
                $tanggalAkhir = Carbon::createFromFormat('m/d/Y', $tanggalAkhirStr)->format('Y-m-d');
                $data = [];
                for ($i = 0; $i <= 31; $i++) {
                    $tanggal = Carbon::createFromFormat('Y-m-d', $tanggalAwal)->addDays($i);
                    if ($tanggal->lte(Carbon::createFromFormat('Y-m-d', $tanggalAkhir))) {
                        $labels[] = $tanggal->format('d M');
                        $dataHari = Shipment::whereDate('created_at', $tanggal)->count();
                        $hasilData[] = $dataHari;
                    }
                }
            }
            return response()->json(['data' => $hasilData, 'labels' => $labels], 200);
        }
        $shipments = Shipment::with(['sender', 'recipient'])->orderBy('created_at', 'desc')->take(5)->get();
        $active = Shipment::active()->get()->count();
        $delivered = Shipment::delivered()->get()->count();
        $courier = Courier::accepted()->get()->count();
        $user = User::role('User')->get()->count();
        return view('admin.dashboard', [
            'shipments' => $shipments,
            'active'    => $active,
            'delivered' => $delivered,
            'courier'   => $courier,
            'user'      => $user
        ]);
    }
}
