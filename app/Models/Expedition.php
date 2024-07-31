<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expedition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'started_at',
        'ended_at',
        'price',
        'price_km',
        'status'
    ];

    public function getPictAttribute()
    {
        return $this->icon && file_exists(public_path('storage/' . $this->icon)) ? asset('storage/' . $this->icon) : 'https://ui-avatars.com/api/?name=' . $this->name . '&color=7F9CF5&background=EBF4FF';
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'expedition_id', 'id');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 1);
    }
}
