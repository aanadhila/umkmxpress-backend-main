<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'courier_name'  => $this->user->name,
            'phonenumber'   => $this->phonenumber,
            'address'       => $this->address,
            'photo'         => $this->pict,
            'nik'           => $this->nik,
            'ktp'           => $this->ktp_pict,
            'nosim'         => $this->nosim,
            'sim'           => $this->sim_pict,
            'nopol'         => $this->nopol,
            'platno'        => $this->platno_pict,
            'vehicle_type ' => $this->vehicle_type,
            'status'        => config('data.courier_status')[$this->status]['label'],
            'user'          => new UserResource($this->user),
        ];
    }
}
