<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'network_scan_id',
        'device_ip',
        'device_mac',
        'device_name',
        'device_type',
        'status',
    ];

    /**
     * Relationship to the NetworkScan model.
     * Each Device belongs to a NetworkScan.
     */
    public function networkScan()
    {
        return $this->belongsTo(NetworkScan::class);
    }
    
}
