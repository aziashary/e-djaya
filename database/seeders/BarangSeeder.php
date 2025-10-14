<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Category;
use Illuminate\Support\Str;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $makanan = Category::where('nama', 'Roti')->first();
        $minuman = Category::where('nama', 'Kopi')->first();

        if (!$makanan || !$minuman) {
            $this->command->warn('⚠️ Jalankan CategorySeeder dulu sebelum BarangSeeder!');
            return;
        }

        $barangList = [
            [
                'nama' => 'Roti Bakar',
                'harga_beli' => 5000,
                'harga_jual' => 10000,
                'category_id' => $makanan->id,
            ],
            [
                'nama' => 'Kopi Susu',
                'harga_beli' => 4000,
                'harga_jual' => 8000,
                'category_id' => $minuman->id,
            ],
        ];

        foreach ($barangList as $item) {
            Barang::create([
                'category_id' => $item['category_id'],
                'nama' => $item['nama'],
                'sku' => 'BRG-' . strtoupper(Str::random(6)),
                'harga_beli' => $item['harga_beli'],
                'harga_jual' => $item['harga_jual'],
                'is_active' => true,
            ]);
        }
    }
}
