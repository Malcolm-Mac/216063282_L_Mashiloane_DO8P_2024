<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('compliance_benchmarks', function (Blueprint $table) {
            $table->id();
            $table->string('os'); // OS for which this benchmark applies
            $table->string('parameter'); // e.g., 'RAM', 'Disk Space'
            $table->string('condition'); // e.g., '>=' for RAM or Disk Space checks
            $table->string('value'); // Minimum value to check
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compliance_benchmarks');
    }
};
