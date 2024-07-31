<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\ApiController;
use Illuminate\Support\Facades\Http;

class ServiceController extends ApiController
{
    public function otp_gateway($phonenumber)
    {
        try {
            $response = Http::withHeaders([
                'Account-Key' => config('ads.account_key'),
                'Service-Key' => config('ads.service_key'),
                'OTP-Service-Key' => config('ads.otp_service_secret_key')
            ])->post(config('ads.otp_url'), [
                'phonenumber' => $phonenumber,
            ]);

            return $response['data'];

        } catch (\Throwable $err){
            return $this->respondInternalError($err->getMessage());
        }
    }

    public function send_wa($data)
    {
        try {
            $response = Http::withHeaders([
                'Account-Key'           => config('ads.account_key'),
                'Service-Key'           => config('ads.service_key'),
                'Whatsapp-Service-Key'  => config('ads.whatsapp_service_secret_key')
            ])->withOptions([
                'verify' => false
            ])->post(config('ads.whatsapp_url'), [
                'phonenumber' => $data['phonenumber'],
                'message'     => $data['message'],
            ]);

            return $response['data'];

        } catch (\Exception $err) {
            abort(500, $err->getMessage());
        }
    }
}
