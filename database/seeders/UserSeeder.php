<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $cabangId = DB::table('cabangs')->where('kode_cabang', '001')->value('id');

        // Admin
        DB::table('users')->insert([
            'name' => 'Admin',
            'nama_lengkap' => 'Administrator',
            'email' => 'admin@local.test',
            'password' => Hash::make('admin12345'),
            'role' => 'ADMIN',
            'cabang_id' => null,
            'aktif' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Pegawai contoh
        DB::table('users')->insert([
            'name' => 'Pegawai 1',
            'nama_lengkap' => 'Pegawai Cabang Utama',
            'email' => 'pegawai1@local.test',
            'password' => Hash::make('pegawai12345'),
            'role' => 'PEGAWAI',
            'cabang_id' => $cabangId,
            'aktif' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
