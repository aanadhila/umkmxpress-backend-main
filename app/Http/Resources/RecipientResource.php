<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecipientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $phonenumber = $this->phonenumber;
        if ($phonenumber[0] == "0") {
            $phonenumber = substr($phonenumber, 1);
        }

        if ($phonenumber[0] == "8") {
            $phonenumber = "62" . $phonenumber;
        }

        return [
            'name'          => $this->name,
            'phonenumber'   => '+' . $phonenumber,
            'address'       => $this->address,
            // 'subdistrict'   => $this->subdistrict->name,
            // 'city'          => $this->subdistrict->city->name,
            // 'province'      => $this->subdistrict->city->province->name,
            // 'postal_code'   => $this->postal_code,
            'short_address' => $this->short_address,
            'note'          => $this->note,
            'default'       => $this->default,
            'latitude'      => $this->latitude,
            'longitude'     => $this->longitude,
        ];
    }
}
