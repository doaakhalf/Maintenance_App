<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalibrationRequest extends Model
{
    use HasFactory;
    protected $fillable = [
       
        'name','request_date','type', 'status', 'equipment_id','requester_id','signed_to_id','request_type','batch_id'
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }
    public function assigned_to()
    {
        return $this->belongsTo(User::class, 'signed_to_id');
    }
    public function calibrationPerform()
    {
        return $this->hasMany(calibrationPerform::class);
    }
    public function assignments()
    {
        return $this->hasMany(CalibrationRequestAssignments::class);
    }
}
