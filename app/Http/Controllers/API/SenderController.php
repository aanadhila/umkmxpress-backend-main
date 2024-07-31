<?php

namespace App\Http\Controllers\API;

use App\Models\City;
use App\Models\User;
use App\Models\Sender;
use App\Models\Province;
use App\Models\Subdistrict;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\SenderResource;
use App\Http\Controllers\API\ApiController;
use Illuminate\Support\Facades\Http;

class SenderController extends ApiController
{
    public function index(Request $request)
    {
        try {
            $senders = Sender::where('courier_id', auth()->user()->courier->id)
                ->when($request->status, function ($query) use ($request) {
                    return $query->where('status', $request->status);
                })->get();
            return $this->respondSuccess('success', SenderResource::collection($senders));
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
            $user = User::find($request->user_id);
            // if (!$user) {
            $request->validate([
                'phonenumber'   => 'required',
                'name'          => 'required',
                'address'       => 'required',
                'province'      => 'required',
                'city'          => 'required',
                'subdistrict'   => 'required',
                // 'latitude'      => 'required',
                // 'longitude'     => 'required',
            ]);
            // }

            $province = Province::where('name', 'like', '%' . $request->province . '%')->firstOrFail();
            $city = City::where('name', 'like', '%' . $request->city . '%')->where('province_id', $province->id)->firstOrFail();
            $subdistrict = Subdistrict::where('name', 'like', '%' . $request->subdistrict . '%')->where('city_id', $city->id)->firstOrFail();


            $city = $request->city;
            $city = strtolower($city);
            $array = explode(' ', $city);
            if ($array[0] == 'kabupaten' || $array[0] == 'kota') {
                $array[0] = '';
            }
            $city = implode(' ', $array);

            $response = Http::withHeaders([
                'ADS-Key' => config('app.denxp_api_key'),
                'Accept' => 'application/json',
            ])->get('https://denxp.com/api/sap-address?province=' . $request->province . '&city=' . $city . '&subdistrict=' . $request->subdistrict);

            if ($response->json()['data'] == null) {
                return $this->respondNotFound('Alamat tidak ditemukan');
            }

            $address = $request->address .', '. $request->city .', '. $request->province .', '. $request->postal_code;

            $responseMaps = Http::get('https://api.distancematrix.ai/maps/api/geocode/json?address=', [
                'address' => $address,
                'region' => 'id',
                'key' => config('app.GEOCODING_API_KEY'),
            ]);
            $data = $responseMaps->json();
    
            if ($responseMaps->ok() && $data['status'] === 'OK') {
                $location = $data['results'][0]['geometry']['location'];
                $latitude = $location['lat'];
                $longitude = $location['lng'];
    
                $request->merge([
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ]);
            } else {
                return $this->respondNotFound('Alamat tidak ditemukan');
            }
            
            $sender = Sender::create([
                'user_id'       => $request->user_id ?? null,
                'name'          => $request->name ? $request->name : $user->name,
                'phonenumber'   => $this->parsingPhonenumber($request->phonenumber ? $request->phonenumber : $user->phonenumber),
                'address'       => $request->address,
                'subdistrict_id' => $subdistrict->id,
                'postal_code'   => $request->postal_code ?? Subdistrict::find($subdistrict->id)->city->postal_code,
                'note'          => $request->note ?? '-',
                'latitude'      => $request->latitude,
                'longitude'     => $request->longitude,
            ]);
            
            DB::commit();
            return $this->respondSuccess('Berhasil menyimpan data pengirim', ['sender_id' => $sender->id, 'sap_id' => $response->json()['data'][0]['id']]);
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
            $shipments = Sender::where('courier_id', auth()->user()->courier->id)
                ->when($request->status, function ($query) use ($request) {
                    return $query->where('status', $request->status);
                })->get()->where('id', $id);
            if ($shipments->isEmpty()) {
                return $this->respondNotFound('shipment not found');
            }
            return $this->respondSuccess('success', new SenderResource($shipments->first()));
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

    public function updateStatus($id)
    {
        //
    }
}
