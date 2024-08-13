<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'equipment_id', 'requester_id', 'status', 'report', 'request_date',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }
}
