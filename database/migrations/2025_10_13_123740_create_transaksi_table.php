<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 30)->unique();
            $table->dateTime('tanggal');
            $table->string('kasir_id');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('diskon', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->string('metode_pembayaran');
            $table->text('catatan')->nullable();
            $table->string('nama_customer', 100)->nullable();
            $table->enum('status', ['selesai', 'batal', 'pending'])->default('selesai');
            $table->timestamps();

            // FK nanti setelah kasir table dibuat
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
    });
    }
};
