<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplianceAssignment extends Model
{
    use HasFactory;
    protected $fillable = ['computer_id', 'benchmark_id'];
}
