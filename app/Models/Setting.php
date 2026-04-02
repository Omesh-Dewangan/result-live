<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'result_live',
        'login_active',
        'result_from',
        'result_to',
        'login_from',
        'login_to',
        'result_template',
    ];

    protected $casts = [
        'result_live' => 'boolean',
        'login_active' => 'boolean',
        'result_from' => 'datetime',
        'result_to' => 'datetime',
        'login_from' => 'datetime',
        'login_to' => 'datetime',
    ];
}
