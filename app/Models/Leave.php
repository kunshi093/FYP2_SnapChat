<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id', // 员工 ID
        'start_date', // 请假开始日期
        'end_date',   // 请假结束日期
        'status',     // 请假状态（pending, approved, rejected）
        'reason',     // 请假原因
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
