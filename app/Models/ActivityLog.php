<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['roll_number', 'view_count', 'print_count', 'ip_address', 'user_agent'];
}
