<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'airway_bill',
        'platform',
        'user_id',
        'sender_id',
        'recipient_id',
        'expedition_id',
        'payment_id',
        'courier_id',
        'distance',
        'shipping_time',
        'total_price',
        'length',
        'width',
        'height',
        'dimension_weight',
        'status',
        'cluster_id',
        'special_cost_id',
        'cost_type',
        'pickup_photo',
        'delivered_photo',
        'new_cluster',
        'new_instant_price',
        'new_sameday_price'
    ];

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class, 'courier_id', 'id');
    }

    public function sender()
    {
        return $this->belongsTo(Sender::class, 'sender_id', 'id');
    }

    public function recipient()
    {
        return $this->belongsTo(Recipient::class, 'recipient_id', 'id');
    }

    public function expedition()
    {
        return $this->belongsTo(Expedition::class, 'expedition_id', 'id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(ShipmentItem::class, 'shipment_id', 'id');
    }

    public function trackers()
    {
        return $this->hasMany(ShipmentTracker::class, 'shipment_id', 'id');
    }

    public function cluster()
    {
        return $this->belongsTo(Cluster::class, 'cluster_id', 'id');
    }

    public function special_cost()
    {
        return $this->belongsTo(SpecialCost::class, 'special_cost_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [2, 3, 4]);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 5);
    }

    public function getPickupPictAttribute()
    {
        return asset('storage/' . $this->pickup_photo);
    }

    public function getDeliveredPictAttribute()
    {
        return asset('storage/' . $this->delivered_photo);
    }
}
