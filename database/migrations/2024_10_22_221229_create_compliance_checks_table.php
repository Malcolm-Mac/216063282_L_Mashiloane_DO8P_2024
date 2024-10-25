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
        Schema::create('compliance_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('computer_id')->constrained('computers')->onDelete('cascade');
            $table->string('parameter'); // E.g., 'RAM', 'Disk Space'
            $table->string('status'); // 'compliant' or 'non-compliant'
            $table->text('details')->nullable(); // Additional details on the check
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compliance_checks');
    }
};
