<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("
            ALTER TABLE prospects
            MODIFY status ENUM('FOLLOW UP','REJECTED','CLOSING')
            NOT NULL DEFAULT 'FOLLOW UP'
        ");

        Schema::table('prospects', function (Blueprint $table) {
            if (!Schema::hasColumn('prospects', 'kab_kota')) {
                $table->string('kab_kota')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('prospects', 'kecamatan')) {
                $table->string('kecamatan')->nullable()->after('kab_kota');
            }
            if (!Schema::hasColumn('prospects', 'desa')) {
                $table->string('desa')->nullable()->after('kecamatan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            if (Schema::hasColumn('prospects', 'kab_kota')) {
                $table->dropColumn('kab_kota');
            }
            if (Schema::hasColumn('prospects', 'kecamatan')) {
                $table->dropColumn('kecamatan');
            }
            if (Schema::hasColumn('prospects', 'desa')) {
                $table->dropColumn('desa');
            }
        });

        DB::statement("
            ALTER TABLE prospects
            MODIFY status ENUM('BELUM_BERMINAT','BERMINAT','TIDAK_BERMINAT','CLOSING')
            NOT NULL DEFAULT 'BELUM_BERMINAT'
        ");
    }
};
