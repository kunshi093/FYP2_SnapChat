<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reason',
        'start_date',
        'end_date',
        'photo',
        'status',
    ];

    // 与用户的关系
    public function staff()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}