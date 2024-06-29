<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenancePerformDetail extends Model
{
    use HasFactory;
    protected $fillable = ['maintenance_perform_id', 'spare_part_id', 'price', 'currency', 'warranty'];

    public function maintenancePerform()
    {
        return $this->belongsTo(MaintenancePerform::class);
    }

    public function sparePart()
    {
        return $this->belongsTo(SparePart::class);
    }
}
