<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prospect_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prospect_id')->index();
            $table->string('file_path', 255);
            $table->string('file_type', 50)->default('foto');
            $table->unsignedBigInteger('uploaded_by')->index();
            $table->timestamps();

            $table->foreign('prospect_id')->references('id')->on('prospects')->cascadeOnDelete();
            $table->foreign('uploaded_by')->references('id')->on('users')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prospect_documents');
    }
};
