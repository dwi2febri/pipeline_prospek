<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prospect_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('prospect_id')->nullable()->index();
            $table->string('title', 150);
            $table->text('message')->nullable();
            $table->string('status', 30)->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('prospect_id')->references('id')->on('prospects')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prospect_notifications');
    }
};
