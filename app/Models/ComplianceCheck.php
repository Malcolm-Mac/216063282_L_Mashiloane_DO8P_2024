<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplianceCheck extends Model
{
    use HasFactory;

    protected $fillable = ['computer_id', 'parameter', 'status','details'];

    public function computer()
    {
        return $this->belongsTo(Computer::class, 'computer_id');
    }
}
