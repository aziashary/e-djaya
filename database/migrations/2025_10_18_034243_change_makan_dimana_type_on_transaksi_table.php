<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            // ubah enum ke string (nullable buat jaga data lama)
            $table->string('makan_dimana', 100)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->enum('makan_dimana', ['dine_in', 'takeaway'])->default('dine_in')->change();
        });
    }
};
