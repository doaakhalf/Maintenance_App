<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalibrationRequestAssignments extends Model
{
    use HasFactory;
    protected $fillable = [
        'calibration_request_id','assigned_by_id','assigned_to_id'
    ];

    public function assigned_to()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }
}

