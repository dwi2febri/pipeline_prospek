<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nama_lengkap', 150)->nullable()->after('name');
            $table->enum('role', ['ADMIN','CABANG','PEGAWAI'])->default('PEGAWAI')->after('email');
            $table->unsignedBigInteger('cabang_id')->nullable()->after('role');
            $table->tinyInteger('aktif')->default(1)->after('cabang_id');
            $table->dateTime('last_login')->nullable()->after('aktif');

            $table->index(['role', 'cabang_id']);
            $table->foreign('cabang_id')->references('id')->on('cabangs')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['cabang_id']);
            $table->dropIndex(['role', 'cabang_id']);

            $table->dropColumn(['nama_lengkap','role','cabang_id','aktif','last_login']);
        });
    }
};
