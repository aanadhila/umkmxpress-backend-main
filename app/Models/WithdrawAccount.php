<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'withdraw_method_id',
        'account_number',
        'courier_wallet_transaction_id',
    ];

    public function withdraw_method()
    {
        return $this->belongsTo(WithdrawMethod::class, 'withdraw_method_id', 'id');
    }

    public function courier_wallet_transaction()
    {
        return $this->belongsTo(CourierWalletTransaction::class, 'courier_wallet_transaction_id', 'id');
    }
}
