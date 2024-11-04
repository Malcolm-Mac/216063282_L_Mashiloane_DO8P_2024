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
        Schema::create('network_scans', function (Blueprint $table) {
            $table->id();
            $table->timestamp('scan_date')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'failed'])->default('pending');
            $table->integer('total_devices_found')->default(0);
            $table->integer('total_online_devices')->default(0);
            $table->integer('scan_duration')->nullable();
            $table->text('scan_errors')->nullable();
            $table->string('network_range')->nullable();
            $table->foreignId('initiated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('computers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('os');
            $table->string('ip_address')->nullable();
            $table->string('mac_address')->nullable();
            $table->enum('status', ['online', 'offline', 'unknown'])->default('unknown');
            $table->timestamp('last_seen_at')->nullable();
            $table->foreignId('network_scan_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('computers');
        Schema::dropIfExists('network_scans');
    }
};
