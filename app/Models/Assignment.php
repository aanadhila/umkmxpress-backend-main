<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'courier_id',
        'shipment_id',
        'cluster_id',
    ];

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}
