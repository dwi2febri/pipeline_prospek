<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) Drop index kalau ada (pakai SQL supaya pasti)
        try {
            DB::statement("DROP INDEX `users_role_cabang_id_index` ON `users`");
        } catch (\Throwable $e) {
            // kalau index tidak ada, abaikan
        }

        // 2) Ubah role jadi VARCHAR(30) (tidak hilangkan data)
        DB::statement("ALTER TABLE `users` MODIFY `role` VARCHAR(30) NOT NULL DEFAULT 'PEGAWAI'");

        // 3) Buat index lagi (opsional) -> tapi aman: buat dengan nama lain supaya tidak bentrok
        // Kalau kamu masih butuh index composite, pakai nama baru:
        try {
            DB::statement("CREATE INDEX `users_role_cabang_id_idx2` ON `users` (`role`, `cabang_id`)");
        } catch (\Throwable $e) {
            // kalau sudah ada index sejenis, abaikan
        }
    }

    public function down(): void
    {
        // drop index baru kalau ada
        try {
            DB::statement("DROP INDEX `users_role_cabang_id_idx2` ON `users`");
        } catch (\Throwable $e) {}

        // balik enum (3 role awal)
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('ADMIN','CABANG','PEGAWAI') NOT NULL DEFAULT 'PEGAWAI'");

        // coba balikin index lama kalau belum ada
        try {
            DB::statement("CREATE INDEX `users_role_cabang_id_index` ON `users` (`role`, `cabang_id`)");
        } catch (\Throwable $e) {}
    }
};
