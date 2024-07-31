<?php

namespace App\Http\Controllers\API;

use App\Models\City;
use App\Models\User;
use App\Models\Province;
use App\Models\Recipient;
use App\Models\Subdistrict;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\RecipientResource;
use App\Http\Controllers\API\ApiController;
use Illuminate\Support\Facades\Http;

class RecipientController extends ApiController
{
    public function index(Request $request)
    {
        try {
            $recipients = Recipient::where('courier_id', auth()->user()->courier->id)
                ->when($request->status, function ($query) use ($request) {
                    return $query->where('status', $request->status);
                })->get();
            return $this->respondSuccess('success', RecipientResource::collection($recipients));
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
            $request->validate([
                'name'          => 'required',
                'phonenumber'   => 'required',
                'province'      => 'required',
                'city'          => 'required',
                'subdistrict'   => 'required',
                'latitude'      => 'required',
                'longitude'     => 'required',
            ]);

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

            $recipient = Recipient::create([
                'user_id'       => $request->user_id ?? null,
                'name'          => $request->name ? $request->name : $user->name,
                'phonenumber'   => $this->parsingPhonenumber($request->phonenumber ? $request->phonenumber : $user->phonenumber),
                'address'       => $request->address,
                'subdistrict_id' => $subdistrict->id,
                'postal_code'   => $request->postal_code ?? Subdistrict::find($request->subdistrict_id)->city->postal_code,
                'note'          => $request->note ?? '-',
                'default'       => $request->default ? 1 : 0,
                'latitude'      => $request->latitude,
                'longitude'     => $request->longitude,
            ]);
            DB::commit();
            return $this->respondSuccess('Berhasil menyimpan data penerima', ['recipient_id' => $recipient->id, 'sap_id' => $response->json()['data'][0]['id']]);
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
            $recipients = Recipient::where('courier_id', auth()->user()->courier->id)
                ->when($request->status, function ($query) use ($request) {
                    return $query->where('status', $request->status);
                })->get()->where('id', $id);
            if ($recipients->isEmpty()) {
                return $this->respondNotFound('shipment not found');
            }
            return $this->respondSuccess('success', new RecipientResource($recipients->first()));
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
