<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kasir;

class KasirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kasir::create([
            'nama' => 'Kasir 1',
            'pin' => '123456',
            'role' => 'kasir',
            'status' => 'aktif',
        ]);
        
    }
}
