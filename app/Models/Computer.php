<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Computer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'ip_address', 'os'];

    // Relationship to ComplianceCheck
    public function complianceChecks()
    {
        return $this->hasMany(ComplianceCheck::class);
    }

    public function complianceAssignments()
    {
        return $this->hasMany(ComplianceAssignment::class);
    }

    // Access benchmarks through assignments
    public function benchmarks()
    {
        return $this->belongsToMany(ComplianceBenchmark::class, 'compliance_assignments');
    }

    public function networkScan()
{
    return $this->belongsTo(NetworkScan::class);
}

    // Retrieve the latest compliance status from ComplianceCheck
    public function getLatestComplianceStatusAttribute()
    {
        $latestCheck = $this->complianceChecks()->latest()->first();
        return $latestCheck ? $latestCheck->status : 'Unknown';
    }
}
