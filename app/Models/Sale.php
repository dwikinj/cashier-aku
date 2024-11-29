<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'total_items',
        'total_price',
        'discount',
        'paid',
        'received', 
        'user_id'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
