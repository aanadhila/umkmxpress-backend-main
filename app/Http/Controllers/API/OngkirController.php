<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OngkirController extends ApiController
{
    public function getOngkir(Request $request)
    {
        try {
            $request->validate([
                'weight' => 'required',
                'origin_id' => 'required',
                'destination_id' => 'required',
            ]);
            $weight = $request->weight/1000;
            $response = Http::withHeaders([
                'ADS-Key' => config('app.denxp_api_key'),
                'Accept' => 'application/json',
            ])->post('https://denxp.com/api/sap-cost', [
                'weight' => $weight,
                'origin_id' => $request->origin_id,
                'destination_id' => $request->destination_id,
            ]);
            return $response->json();
        } catch (\Throwable $th) {
            return $this->respondInternalError($th->getMessage());
        }
    }
}
