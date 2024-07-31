<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourierWalletTransactionResource extends JsonResource
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
                'amount'        => 'Rp ' . number_format($this->amount, 0, ',', '.'),
                'type_id'       => $this->type,
                'type'          => config('data.transaction_type_label')[$this->type],
                'status'        => config('data.payment_status')[$this->status]['label'],
                'note'          => $this->note,
                'created_at'    => date('d-m-Y H:i', strtotime($this->created_at)),
                'withdraw_account' => new WithdrawAccountResource($this->withdraw_account),
        ];
    }
}
