<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CabangSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cabangs')->insert([
            [
                'kode_cabang' => '001',
                'nama_cabang' => 'KC Utama',
                'alamat' => 'Semarang',
                'aktif' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
