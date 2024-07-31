<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'method_image',
    ];

    public function getWithdrawPictAttribute()
    {
        return asset('storage/' . $this->method_image);
    }

    public function withdraw_accounts()
    {
        return $this->hasMany(WithdrawAccount::class, 'withdraw_method_id', 'id');
    }
}
