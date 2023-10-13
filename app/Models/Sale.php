<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'total_item',
        'total_price',
        'discont',
        'pay',
        'accepted',
        'user_id',
    ];
    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id');
    }
}
