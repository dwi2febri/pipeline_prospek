<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->unsignedBigInteger('estimasi_nominal_realisasi')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->dropColumn('estimasi_nominal_realisasi');
        });
    }
};
