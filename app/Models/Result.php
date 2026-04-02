<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    public function activitySummary()
    {
        return $this->hasOne(ActivityLog::class, 'roll_number', 'roll_number');
    }

    protected $fillable = [
        'roll_number',
        'name',
        'father_name',
        'course',
        'subject1',
        'subject2',
        'subject3',
        'subject4',
        'subject5',
        'total',
        'result_status',
    ];
}
