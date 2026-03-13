<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prospect_status_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prospect_id')->index();
            $table->string('from_status', 30)->nullable();
            $table->string('to_status', 30);
            $table->unsignedBigInteger('changed_by')->index();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('prospect_id')->references('id')->on('prospects')->cascadeOnDelete();
            $table->foreign('changed_by')->references('id')->on('users')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prospect_status_histories');
    }
};
