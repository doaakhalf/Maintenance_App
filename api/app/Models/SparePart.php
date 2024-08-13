<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'equipment_id',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
    public function sparePartRequestDetails()
    {
        return $this->hasMany(SparePartRequestDetail::class);
    }
    public function sparePartPerformDetails()
    {
        return $this->hasMany(SparePartPerformDetail::class);
    }
    public function maintenancePerformDetails()
    {
        return $this->hasMany(MaintenancePerformDetail::class);
    }
}
