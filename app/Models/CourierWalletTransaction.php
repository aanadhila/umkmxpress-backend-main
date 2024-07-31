<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierWalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'courier_wallet_id',
        'type',
        'amount',
        'status',
        'note',
        'payment_method_id',
        'account_number',
        'account_name'
    ];

    public function wallet()
    {
        return $this->belongsTo(CourierWallet::class, 'courier_wallet_id', 'id');
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }

    public function withdraw_account()
    {
        return $this->hasOne(WithdrawAccount::class, 'courier_wallet_transaction_id', 'id');
    }
}
