<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sender extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phonenumber',
        'address',
        'subdistrict_id',
        'postal_code',
        'note',
        'latitude',
        'longitude'
    ];

    public function getFullAddressAttribute()
    {
        return $this->address . ', ' . $this->subdistrict->name . ', ' . $this->subdistrict->city->name . ', ' . $this->subdistrict->city->province->name . ' ' . $this->postal_code;
    }

    public function getShortAddressAttribute()
    {
        return $this->address . ', ' . $this->subdistrict->city->name;
    }

    public function subdistrict()
    {
        return $this->belongsTo(Subdistrict::class, 'subdistrict_id', 'id');
    }

    public function shipments()
    {
        return $this->belongsTo(Shipment::class, 'sender_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
