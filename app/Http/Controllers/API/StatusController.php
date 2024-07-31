<?php

namespace App\Http\Controllers\API;

class StatusController extends ApiController
{
    public function getShipmentStatus() {
        return $this->respondSuccess('success', config('data.shipment_status'));
    }
}
