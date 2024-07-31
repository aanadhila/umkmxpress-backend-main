<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'verification',
        'cost',
        'available',
        'account_number',
        'account_name',
        'icon'
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'payment_method_id', 'id');
    }

    public function courier_wallet_transaction()
    {
        return $this->hasMany(CourierWalletTransaction::class, 'courier_wallet_id', 'id');
    }

    public function getPictAttribute()
    {
        return $this->icon && file_exists(public_path('storage/' . $this->icon)) ? asset('storage/' . $this->icon) : 'https://ui-avatars.com/api/?name=' . $this->name . '&color=7F9CF5&background=EBF4FF';
    }

    public function scopeAvailable($query)
    {
        return $query->where('available', 1);
    }
}
