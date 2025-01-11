<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // 明确指定模型对应的表为 users
    protected $table = 'users';

    public function clockInRecords()
    {
        return $this->hasMany(ClockIn::class, 'user_id'); // 确保 user_id 是正确的外键
    }
    
    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_photo', // 添加头像
    ];

    /**
     * 判断是否为管理员
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * 判断是否为员工
     */
    public function isStaff()
    {
        return $this->role === 'staff';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}