<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClusterController extends Controller
{
    public function getCluster($lat, $lon) {
        $response = Http::post('http://127.0.0.1:5000', [
            'lat' => $lat,
            'lon' => $lon,

        ]);
        // Cek status respons
        if ($response->successful()) {
            // Mengembalikan hasil respons sebagai JSON
            return response()->json($response->json());
        } else {
            // Mengembalikan pesan kesalahan jika permintaan gagal
            return response()->json(['error' => 'Failed to get cluster data'], $response->status());
        }

    }
}
