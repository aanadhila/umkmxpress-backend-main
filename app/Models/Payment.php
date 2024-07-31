<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_method_id',
        'payment_link',
        'total_payment',
        'status',
        'status_updated_at',
    ];

    public function method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class, 'payment_id', 'id');
    }

    public function histories()
    {
        return $this->hasMany(TransactionHistory::class, 'payment_id', 'id');
    }
}
