<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdistrict extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'name',
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function recipients()
    {
        return $this->hasMany(Recipient::class, 'subdistrict_id', 'id');
    }

    public function senders()
    {
        return $this->hasMany(Sender::class, 'subdistrict_id', 'id');
    }

    public function specialCostOrigins()
    {
        return $this->hasMany(SpecialCost::class, 'origin_subdistrict_id', 'id');
    }

    public function specialCostDestinations()
    {
        return $this->hasMany(SpecialCost::class, 'destination_subdistrict_id', 'id');
    }

    public function coverage()
    {
        return $this->hasMany(ClusterCoverage::class, 'subdistrict_id', 'id');
    }
}
