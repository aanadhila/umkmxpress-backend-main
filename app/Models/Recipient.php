<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
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
        'default',
        'latitude',
        'longitude'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getShortAddressAttribute()
    {
        return $this->address . ', ' . $this->subdistrict->city->name;
    }

    public function subdistrict()
    {
        return $this->belongsTo(Subdistrict::class, 'subdistrict_id', 'id');
    }

    public function getFullAddressAttribute()
    {
        return $this->address . ', ' . $this->subdistrict->name . ', ' . $this->subdistrict->city->name . ', ' . $this->subdistrict->city->province->name . ' ' . $this->postal_code;
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'recipient_id', 'id');
    }
}
