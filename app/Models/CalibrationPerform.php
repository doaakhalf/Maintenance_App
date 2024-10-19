<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalibrationPerform extends Model
{
    use HasFactory;
    protected $fillable = [
        'calibration_request_id', 'technician_id', 'status', 'service_report', 'perform_date','requester_id','performed_by_id',
    ];
   

    public function calibrationRequest()
    {
        return $this->belongsTo(CalibrationRequest::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }
    public function performed_by()
    {
        return $this->belongsTo(User::class, 'performed_by_id');
    }

    public function performDetails()
    {
        return $this->hasMany(CalibrationPerformDetail::class);
    }
}
