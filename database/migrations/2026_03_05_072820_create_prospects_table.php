<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prospects', function (Blueprint $table) {
            $table->id();

            $table->date('tanggal_prospek')->index();
            $table->string('nama', 150);
            $table->string('nik', 30)->nullable()->index();
            $table->string('no_hp', 30)->nullable()->index();
            $table->string('alamat', 255)->nullable();

            $table->text('keterangan_usaha')->nullable();
            $table->decimal('lokasi_lat', 10, 7)->nullable();
            $table->decimal('lokasi_lng', 10, 7)->nullable();

            $table->enum('jenis_produk', ['DANA','KREDIT','LAINNYA'])->default('KREDIT')->index();
            $table->enum('status', ['BELUM_BERMINAT','BERMINAT','TIDAK_BERMINAT','CLOSING'])
                  ->default('BELUM_BERMINAT')->index();

            $table->unsignedBigInteger('cabang_id')->index();
            $table->unsignedBigInteger('input_by')->index();
            $table->unsignedBigInteger('referral_user_id')->nullable()->index();

            $table->text('catatan')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('cabang_id')->references('id')->on('cabangs')->restrictOnDelete();
            $table->foreign('input_by')->references('id')->on('users')->restrictOnDelete();
            $table->foreign('referral_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prospects');
    }
};
