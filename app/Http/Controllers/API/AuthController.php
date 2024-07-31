<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ServiceController;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends ApiController
{
    public function requestOTP($phonenumber = null)
    {
        try {
            if (!$phonenumber) {
                request()->validate([
                    'phonenumber'   => 'required',
                ], [
                    'phonenumber.required'  => 'Nomor telepon tidak boleh kosong!'
                ]);
                $phonenumber = $this->parsingPhonenumber(request()->phonenumber);
            }
            $user = User::firstWhere('phonenumber', $phonenumber);
            if (!$user) {
                return $this->respondUnauthorized('Akun tidak ditemukan!');
            }
            if ($user->otp_daily >= 5) {
                return $this->respondForbidden('Anda sudah meminta OTP lebih dari 5 kali hari ini, coba lagi besok');
            }
            $service = new ServiceController;
            $otp = $service->otp_gateway($phonenumber);

            $user->update([
                'otp'               => $otp,
                'otp_daily'         => $user->otp_daily + 1,
                'otp_expired_at'    => Carbon::now()->addMinutes(10),
            ]);

            if (request()->route()->getName() == 'requestOTP') {
                return $this->respondSuccess('Berhasil mengirim OTP', ['otp' => $otp]);
            }

            return $this->respondSuccess('Berhasil mengirim OTP', ['otp' => $otp]);
        } catch (\Throwable $th) {
            return $this->respondInternalError($th->getMessage());
        }
    }

    public function login(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'phonenumber'   => 'required',
            ], [
                'phonenumber.required'  => 'Nomor telepon tidak boleh kosong!'
            ]);

            $phonenumber = $this->parsingPhonenumber($request->phonenumber);
            $otp = $this->requestOTP($phonenumber);

            DB::commit();
            return $otp;
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->respondInternalError($th->getMessage());
        }
    }

    public function checkToken()
    {
        try {
            //Check bearer token is valid or not
            if (auth('sanctum')->check()) {
                return $this->respondSuccess('true');
            } else {
                return $this->respondSuccess('false');
            }
        } catch (\Throwable $th) {
            return $this->respondInternalError($th->getMessage());
        }
    }

    public function validateOTP(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'phonenumber'   => 'required',
                'otp'           => 'required',
            ], [
                'phonenumber.required'  => 'Nomor telepon tidak boleh kosong!',
                'otp.required'          => 'OTP tidak boleh kosong'
            ]);

            $phonenumber = $this->parsingPhonenumber($request->phonenumber);

            $user = User::firstWhere('phonenumber', $phonenumber);

            if ($user->otp != $request->otp) {
                return $this->respondForbidden('OTP tidak sesuai!');
            }

            if (Carbon::now()->gt($user->otp_expired_at)) {
                return $this->respondUnauthorized('OTP kadaluarsa, silahkan meminta OTP kembali!');
            }

            $token = $user->createToken('token')->plainTextToken;

            $user->update([
                'otp'               => null,
                'otp_expired_at'    => null,
                'token'             => $token,
            ]);

            DB::commit();
            return $this->respondSuccess('OTP berhasil diverifikasi', ['token' => $token]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->respondInternalError($th->getMessage());
        }
    }

    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'phonenumber'   => 'required|unique:users,phonenumber',
            ], [
                'phonenumber.required'  => 'Nomor telepon tidak boleh kosong!',
                'phonenumber.unique'    => 'Nomor telepon sudah digunakan!',
            ]);

            $phonenumber = $this->parsingPhonenumber($request->phonenumber);

            if (User::firstWhere('phonenumber', $phonenumber)) {
                return $this->respondForbidden('Nomor telepon sudah digunakan!');
            }

            User::create([
                'phonenumber'   => $phonenumber,
            ])->assignRole('User');
            $otp = $this->requestOTP($phonenumber);

            DB::commit();
            return $otp;
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->respondInternalError($th->getMessage());
        }
    }

    public function resetDailyOTP(Request $request)
    {
        try {
            $request->validate([
                'phonenumber'   => 'required'
            ]);

            $user = User::firstWhere('phonenumber', $this->parsingPhonenumber($request->phonenumber));
            if (!$user) return $this->respondNotFound('User tidak ditemukan!');
            $user->update([
                'otp_daily' => 0,
            ]);
            return $this->respondSuccess('OTP berhasil direset!');
        } catch (\Throwable $th) {
            return $this->respondInternalError($th->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            auth()->user()->update([
                'token' => null,
            ]);
            $request->user()->currentAccessToken()->delete();
            return $this->respondSuccess('Berhasil logout!');
        } catch (\Throwable $th) {
            return $this->respondInternalError($th->getMessage());
        }
    }
}
