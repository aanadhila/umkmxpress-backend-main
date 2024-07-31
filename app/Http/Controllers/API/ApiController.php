<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\FlareClient\Api;

class ApiController extends Controller
{

    public function respond($message = 'Success', $data = null, $statusCode = 200, $headers = [])
    {
        $status = "" . $statusCode;
        if ($data instanceof JsonResource) {
            return $data->additional(compact('message', 'status'));
        }
        $respondData = compact('data', 'message', 'status');
        return response()->json($respondData, $statusCode, $headers);
    }

    public function respondSuccess($message = null, $data = null, $headers = [])
    {
        $message = $message ?? __('response.200');
        return $this->respond($message, $data, 200, $headers);
    }

    public function respondCreated($message = null, $data = null, $headers = [])
    {
        $message = $message ?? __('response.201');
        return $this->respond($message, $data, 201, $headers);
    }

    public function respondAccepted($message = null, $data = null, $headers = [])
    {
        $message = $message ?? __('response.202');
        return $this->respond($message, $data, 202, $headers);
    }

    public function respondNoContent($message = ' ', $headers = [])
    {
        return $this->respond($message, null, 204, $headers);
    }

    public function respondInvalid($message = null, $error = null, $headers = [])
    {
        $message = $message ?? __('response.400');
        return $this->respond($message, $error, 400, $headers);
    }

    public function respondUnauthorized($message = null, $error = null, $headers = [])
    {
        $message = $message ?? __('response.401');
        return $this->respond($message, $error, 401, $headers);
    }

    public function respondForbidden($message = null, $error = null, $headers = [])
    {
        $message = $message ?? __('response.403');
        return $this->respond($message, $error, 403, $headers);
    }

    public function respondNotFound($message = null, $error = null, $headers = [])
    {
        $message = $message ?? __('response.404');
        return $this->respond($message, $error, 404, $headers);
    }

    public function respondInternalError($message = null, $error = null, $headers = [])
    {
        $message = $message ?? __('response.500');
        return $this->respond($message, $error, 500, $headers);
    }

    public function parsingPhonenumber($phonenumber)
    {
        if ($phonenumber[0] == "0") {
            $phonenumber = substr($phonenumber, 1);
        }

        if ($phonenumber[0] == "8") {
            $phonenumber = "62" . $phonenumber;
        }

        return $phonenumber;
    }
}
