<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'kode')) {
                $table->string('kode', 30)->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'employee_id')) {
                $table->string('employee_id', 64)->nullable()->after('kode')->index();
            }
            if (!Schema::hasColumn('users', 'branch_name')) {
                $table->string('branch_name', 180)->nullable()->after('cabang_id');
            }
            if (!Schema::hasColumn('users', 'unit_kerja')) {
                $table->string('unit_kerja', 180)->nullable()->after('branch_name');
            }
            if (!Schema::hasColumn('users', 'level')) {
                $table->string('level', 120)->nullable()->after('job_position');
            }
            if (!Schema::hasColumn('users', 'group_jabatan')) {
                $table->string('group_jabatan', 120)->nullable()->after('level');
            }
        });

        Schema::create('user_simpeg_syncs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('employee_id', 64)->unique();
            $table->string('sync_status', 16)->index();
            $table->string('sync_message', 500)->nullable();
            $table->json('snapshot_data')->nullable();
            $table->timestamp('synced_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_simpeg_syncs');
    }
};
