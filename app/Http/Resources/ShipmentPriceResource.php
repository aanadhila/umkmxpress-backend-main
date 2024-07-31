<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentPriceResource extends JsonResource
{
    protected $customMessage;

    public function __construct($resource, $customMessage)
    {
        parent::__construct($resource);
        $this->customMessage = $customMessage;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $shipment_price = 0;
        $admin_fee = 1000;
        if ($this->expedition != null){
            $shipment_price = $this->total_price;
        } else if ($this->cluster != null){
            if ($this->cost_type == 'next_day') {
                $shipment_price = $this->cluster->next_day_cost;
            } else {
                $shipment_price = $this->cluster->instant_courier_cost;
            }
        } else if ($this->special_cost != null){
            $shipment_price = $this->special_cost->cost;
        }

        return [
            'main_message'      => $this->customMessage,
            'shipment_price'    => $shipment_price,
            'admin_fee'         => $admin_fee,
            'total_price'       => $shipment_price + $admin_fee,
        ];
    }
}
