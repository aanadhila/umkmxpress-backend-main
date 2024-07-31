<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JourneyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Delivery
        if ($this->status >= 4) {
            // $id = $this->recipient->id;
            $name = $this->recipient->name;
            $phonenumber = $this->recipient->phonenumber;
            if ($phonenumber[0] == "0") {
                $phonenumber = substr($phonenumber, 1);
            }

            if ($phonenumber[0] == "8") {
                $phonenumber = "62" . $phonenumber;
            }
            $address = $this->recipient->short_address;
        } 
        // Pickup
        else {
            // $id = $this->sender->id;
            $name = $this->sender->name;
            $phonenumber = $this->sender->phonenumber;
            if ($phonenumber[0] == "0") {
                $phonenumber = substr($phonenumber, 1);
            }

            if ($phonenumber[0] == "8") {
                $phonenumber = "62" . $phonenumber;
            }
            $address = $this->sender->short_address;
        }

        return [
            'shipment_id'                => $this->id,
            'name'              => $name,
            'phonenumber'       => $phonenumber,
            'status_shipment'   => config('data.shipment_status')[$this->status]['label'],
            'address'           => $address,
        ];
    }
}
