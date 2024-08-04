<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
       
    ];

  
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class,'role_id');
    }
    public function hasPermission($permission)
    {
        return $this->role->permissions->contains('permission_name', $permission);
    }
    public function hasRole($roleName)
    {
        return $this->role && $this->role->role_name == $roleName;
    }

    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class, 'requester_id');
    }

    public function maintenancePerforms()
    {
        return $this->hasMany(MaintenancePerform::class, 'technician_id');
    }

    public function calibrationRequests()
    {
        return $this->hasMany(CalibrationRequest::class, 'requester_id');
    }

    public function calibrationPerforms()
    {
        return $this->hasMany(CalibrationPerform::class, 'technician_id');
    }
    public function sparePartRequests()
    {
        return $this->hasMany(SparePartRequest::class, 'requester_id');
    }

    public function sparePartPerforms()
    {
        return $this->hasMany(SparePartPerform::class, 'technician_id');
    }

    public function repairRequests()
    {
        return $this->hasMany(RepairRequest::class, 'requester_id');
    }
    public function unreadNotifications()
    {
        return $this->notifications()->orderBy('created_at', 'desc')->whereNull('read_at');
    }
}
