<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartTimeStaff extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'referred_by',
        'position',
        'registered_at',
    ];
}