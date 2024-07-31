<?php

namespace App\Http\Resources;

use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $shipment_price = 0;
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

        $status = 0;
        if ($this->status >= 4){
            $status = 1;
        }

        return [
            'id'            => $this->id,
            'airway_bill'   => $this->airway_bill,
            'platform'      => $this->platform ?? null,
            'distance'      => $this->distance,
            'date'          => $this->created_at->format('d-m-Y'),
            'shipping_time' => $this->shipping_time,
            // 'length'        => $this->length,
            // 'width'         => $this->width,
            // 'height'        => $this->height,
            // 'dimension_weight'  => $this->dimension_weight,
            'shipment_price'    => $shipment_price,
            'admin_fee'         => $this->payment->method->cost,
            'total_price'       => $shipment_price + $this->payment->method->cost,
            'status_shipment'   => config('data.shipment_status')[$this->status]['label'],
            'onWarehouse'  => $status,
            'sender'        => new SenderResource($this->sender),
            'recipient'     => new RecipientResource($this->recipient),
            'items'         => ItemResource::collection($this->items),
            'expedition'    => new ExpeditionResource($this->expedition),
        ];
    }
}
