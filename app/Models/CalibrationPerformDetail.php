<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalibrationPerformDetail extends Model
{
    use HasFactory;
    protected $fillable = ['calibration_perform_id', 'spare_part_id', 'price', 'currency', 'warranty','quantity','warranty_unit'];

    public function calibrationPerform()
    {
        return $this->belongsTo(CalibrationPerform::class);
    }

    public function sparePart()
    {
        return $this->belongsTo(SparePart::class);
    }
}
