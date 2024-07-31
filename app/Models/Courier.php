<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phonenumber',
        'address',
        'photo',
        'nik',
        'ktp',
        'nosim',
        'sim',
        'nopol',
        'platno',
        'vehicle_type',
        'status',
        'status_updated_at',
        'cluster_id',
        'courier_specialist',
        'new_cluster'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getPictAttribute()
    {
        return $this->photo && file_exists(public_path('storage/' . $this->photo)) ? asset('storage/' . $this->photo) : 'https://ui-avatars.com/api/?name=' . $this->user->name . '&color=7F9CF5&background=EBF4FF';
    }

    public function getKtpPictAttribute()
    {
        return asset('storage/' . $this->ktp);
    }

    public function getSimPictAttribute()
    {
        return asset('storage/' . $this->sim);
    }

    public function getPlatnoPictAttribute()
    {
        return asset('storage/' . $this->platno);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'courier_id', 'id');
    }

    public function scopeAccepted($query) {
        return $query->where('status', 2);
    }

    public function cluster()
    {
        return $this->belongsTo(Cluster::class, 'cluster_id', 'id');
    }

    public function wallet()
    {
        return $this->hasOne(CourierWallet::class, 'courier_id', 'id');
    }
}
