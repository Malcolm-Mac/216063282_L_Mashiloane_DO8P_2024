<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkScan extends Model
{
    use HasFactory;

    protected $fillable = [
        'scan_date',
        'status',
        'total_devices_found',
        'total_online_devices',
        'scan_duration',
        'scan_errors',
        'network_range',
        'initiated_by',
    ];

    /**
     * Relationship to the Device model.
     * Each NetworkScan can have multiple Devices.
     */
    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function computers()
    {
        return $this->hasMany(Computer::class);
    }

    /**
     * Relationship to the User model for tracking who initiated the scan.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

}
