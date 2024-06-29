<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','request_date','type', 'status', 'equipment_id', 'signed_to_id','requester_id',
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
    public function maintenancePerforms()
    {
        return $this->hasMany(MaintenancePerform::class);
    }
}
