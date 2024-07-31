<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\CourierResource;
use App\Models\Courier;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CourierController extends ApiController
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name'  => 'required',
                'photo' => 'mimes:png,jpg|max:10240',
                'nik'   => 'required|unique:couriers,nik',
                'ktp'   => 'mimes:png,jpg|max:10240',
                'nosim' => 'required|unique:couriers,nosim',
                'sim'   => 'mimes:png,jpg|max:10240',
                'nopol' => 'required|unique:couriers,nopol',
                'platno'  => 'mimes:png,jpg|max:10240',
                'vehicle_type'  => 'required'
                // 'phonenumber'  => 'required|unique:couriers,phonenumber',
            ]);
            $photo      = $request->file('photo');

            $photo_name = time() . '_photo_' . $photo->getClientOriginalExtension();
            $photo_path = $photo->storeAs('couriers', $photo_name, 'public');

            $ktp      = $request->file('ktp');
            $ktp_name = time() . '_ktp_' . $ktp->getClientOriginalExtension();
            $ktp_path = $ktp->storeAs('couriers', $ktp_name, 'public');

            $sim      = $request->file('sim');
            $sim_name = time() . '_sim_' . $sim->getClientOriginalExtension();
            $sim_path = $sim->storeAs('couriers', $sim_name, 'public');

            $platno      = $request->file('platno');
            $platno_name = time() . '_platno_' . $platno->getClientOriginalExtension();
            $platno_path = $platno->storeAs('couriers', $platno_name, 'public');

            $user = auth()->user();
            $user->update([
                'name'  => $request->name,
            ]);
            $user->courier()->create([
                'user_id'   => $request->user_id,
                'nik'       => $request->nik,
                'nopol'     => $request->nopol,
                'phonenumber' => $this->parsingPhonenumber($request->phonenumber ?? $user->phonenumber),
                'address'   => $request->address,
                'photo'     => $photo_path,
                'ktp'       => $ktp_path,
                'nosim'     => $request->nosim,
                'sim'       => $sim_path,
                'platno'      => $platno_path,
                'vehicle_type' => $request->vehicle_type
                'status'    => 1,
                'status_updated_at' => new DateTime(),
            ]);
            DB::commit();
            return $this->respondSuccess('Berhasil menyimpan data identitas', new CourierResource($user->courier));
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->respondInternalError($th->getMessage());
        }
    }

    public function show() {
        try {
            $user = auth()->user();
            $courier = $user->courier;
            if (!$courier || !$courier->phonenumber) throw new \Exception('Harap menyelesaikan registrasi kurir terlebih dahulu');
            if ($courier->status == 3) throw new \Exception('Registrasi anda ditolak oleh admin, silahkan hubungi admin untuk informasi lebih lanjut');
            if ($courier->status != 2) return $this->respondAccepted('Menunggu verifikasi dari admin');
            return $this->respondSuccess('success', new CourierResource($user->courier));
        } catch (\Throwable $th) {
            return $this->respondInternalError($th->getMessage());
        }
    }

    public function update(Request $request) {
        DB::beginTransaction();
        try {
            $user = auth()->user();
            $courier = $user->courier;
            $request->validate([
                'nik'   => 'unique:couriers,nik',
                'nosim' => 'unique:couriers,nosim',
                'nopol' => 'unique:couriers,nopol',
                'phonenumber'  => 'unique:couriers,phonenumber',
            ]);
            if ($request->file('photo')) {
                $photo      = $request->file('photo');
                $photo_name = time() . '_photo_' . $photo->getClientOriginalExtension();
                $photo_path = $photo->storeAs('couriers', $photo_name, 'public');
                $old_photo = $courier->photo;
                $courier->update([
                    'photo' => $photo_path,
                ]);
                Storage::disk('public')->delete($old_photo);
            }

            if ($request->file('ktp')) {
                $ktp      = $request->file('ktp');
                $ktp_name = time() . '_ktp_' . $ktp->getClientOriginalExtension();
                $ktp_path = $ktp->storeAs('couriers', $ktp_name, 'public');
                $old_ktp = $courier->ktp;
                $courier->update([
                    'ktp' => $ktp_path,
                ]);
                Storage::disk('public')->delete($old_ktp);
            }

            if ($request->file('sim')) {
                $sim      = $request->file('sim');
                $sim_name = time() . '_sim_' . $sim->getClientOriginalExtension();
                $sim_path = $sim->storeAs('couriers', $sim_name, 'public');
                $old_sim = $courier->sim;
                $courier->update([
                    'sim' => $sim_path,
                ]);
                Storage::disk('public')->delete($old_sim);
            }

            if ($request->file('platno')) {
                $platno      = $request->file('platno');
                $platno_name = time() . '_platno_' . $platno->getClientOriginalExtension();
                $platno_path = $platno->storeAs('couriers', $platno_name, 'public');
                $old_platno = $courier->platno;
                $courier->update([
                    'platno' => $platno_path,
                ]);
                Storage::disk('public')->delete($old_platno);
            }

            $courier->update([
                'nik'           => $request->nik ?? $courier->nik,
                'nopol'         => $request->nopol ?? $courier->nopol,
                'phonenumber'   => $this->parsingPhonenumber($request->phonenumber ?? $courier->phonenumber),
                'nosim'         => $request->nosim ?? $courier->nosim,
                'vehicle_type'  => $request->vehicle_type ?? $courier->vehicle_type,
                'address'       => $request->address ?? $courier->address,
            ]);
            $courier->user->update([
                'name'  => $request->name ?? $courier->user->name,
            ]);
            DB::commit();
            return $this->respondSuccess('success', new CourierResource($courier));
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->respondInternalError($th->getMessage());
        }
    }
}
