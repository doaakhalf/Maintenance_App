<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalibrationPerform extends Model
{
    use HasFactory;
    protected $fillable = [
        'calibration_request_id', 'technician_id', 'status', 'service_report', 'perform_date',
    ];

    public function calibrationRequest()
    {
        return $this->belongsTo(CalibrationRequest::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
