<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kasir', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->char('pin', 6)->unique(); // PIN login (6 digit)
            $table->enum('role', ['kasir', 'supervisor'])->default('kasir');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kasir');
    }
};
