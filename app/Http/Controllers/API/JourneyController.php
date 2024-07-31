<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\JourneyResource;
use App\Models\Shipment;
use Illuminate\Http\Request;

class JourneyController extends ApiController
{
    public function pickup(Request $request){
        try {
            $journeys = Shipment::where('courier_id', auth()->user()->courier->id)
            ->when($request->status, function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->where('status', '>', 1)
            ->where('status', '<', 4)
            ->get();
            if ($journeys->count() == 0) {
                return $this->respondNotFound('shipment not found');
            }
            return $this->respondSuccess('success', JourneyResource::collection($journeys));
        } catch (\Throwable $th) {
            return $this->respondInternalError($th->getMessage());
        }
    }
    public function delivery(Request $request){
        try {
            $journeys = Shipment::where('courier_id', auth()->user()->courier->id)
            ->when($request->status, function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->where('status', '>', 5)
            ->where('status', '<', '8')
            ->get();
            if ($journeys->count() == 0) {
                return $this->respondNotFound('shipment not found');
            }
            return $this->respondSuccess('success', JourneyResource::collection($journeys));
        } catch (\Throwable $th) {
            return $this->respondInternalError($th->getMessage());
        }
    }
    
}
