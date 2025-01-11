<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClockIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'clock_in_time',
        'clock_out_time',
        'photo', // 添加 photo 字段
    ];

    protected $casts = [
        'clock_in_time' => 'datetime:Y-m-d H:i:s',
        'clock_out_time' => 'datetime:Y-m-d H:i:s',
    ];
}