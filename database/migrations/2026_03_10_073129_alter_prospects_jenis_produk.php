<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->string('jenis_produk', 20)->change(); // biar muat TABUNGAN/DEPOSITO/ASET
        });
    }

    public function down(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->string('jenis_produk', 10)->change(); // sesuaikan kalau dulu bukan 10
        });
    }
};
