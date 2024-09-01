<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'sn',
        'image',
        'model',
        'class',
        'price',
        'ppm',
        'ppm_unit',
        'need_calibration',
        'calibration_cycle',
         'department_id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function spareParts()
    {
        return $this->hasMany(SparePart::class);
    }

    public function sparePartRequests()
    {
        return $this->hasMany(SparePartRequest::class);
    }
    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function calibrationRequests()
    {
        return $this->hasMany(CalibrationRequest::class);
    }
}
