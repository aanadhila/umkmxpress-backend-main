<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'next_day_cost',
        'instant_courier_cost'
    ];

    public function coverages() {
        return $this->hasMany(ClusterCoverage::class, 'cluster_id', 'id');
    }

    public function couriers() {
        return $this->hasMany(Courier::class, 'cluster_id', 'id');
    }
}
