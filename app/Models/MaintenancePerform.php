<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenancePerform extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'maintenance_request_id', 'technician_id','requester_id', 'status', 'service_report', 'perform_date','performed_by_id',
    ];

    public function maintenanceRequest()
    {
        return $this->belongsTo(MaintenanceRequest::class);
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
        return $this->hasMany(MaintenancePerformDetail::class);
    }
}
