<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'province_id',
        'name',
        'postal_code'
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    public function subdistricts()
    {
        return $this->hasMany(Subdistrict::class, 'city_id', 'id');
    }
}
