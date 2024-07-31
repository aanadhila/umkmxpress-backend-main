<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'courier_id',
        'balance',
    ];

    public function courier()
    {
        return $this->belongsTo(Courier::class, 'courier_id', 'id');
    }

    public function transaction()
    {
        return $this->hasMany(CourierWalletTransaction::class, 'courier_wallet_id', 'id');
    }
}
