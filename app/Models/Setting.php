<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_name',
        'company_address',
        'company_phone',
        'member_discount',
        'logo_path',
        'member_card_path'
    ];
}
