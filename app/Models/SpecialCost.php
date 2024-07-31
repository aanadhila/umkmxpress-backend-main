<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin_subdistrict_id',
        'destination_subdistrict_id',
        'cost',
    ];

    public function origin()
    {
        return $this->belongsTo(Subdistrict::class, 'origin_subdistrict_id', 'id');
    }

    public function destination()
    {
        return $this->belongsTo(Subdistrict::class, 'destination_subdistrict_id', 'id');
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'special_cost_id', 'id');
    }
}
