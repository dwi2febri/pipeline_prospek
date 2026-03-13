<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {

            // tentukan kolom acuan untuk "after"
            $afterUserId = Schema::hasColumn('audit_logs', 'user_id');
            $afterAction = Schema::hasColumn('audit_logs', 'action');
            $afterType   = Schema::hasColumn('audit_logs', 'type');
            $afterModel  = Schema::hasColumn('audit_logs', 'model_id');
            $afterIp     = Schema::hasColumn('audit_logs', 'ip');
            $afterUA     = Schema::hasColumn('audit_logs', 'user_agent');

            // actor_name
            if (!Schema::hasColumn('audit_logs', 'actor_name')) {
                $col = $table->string('actor_name', 150)->nullable();
                if ($afterUserId) $col->after('user_id');
            }

            // type
            if (!Schema::hasColumn('audit_logs', 'type')) {
                $col = $table->string('type', 120)->nullable();
                if ($afterAction) $col->after('action');
            }

            // model_id
            if (!Schema::hasColumn('audit_logs', 'model_id')) {
                $col = $table->string('model_id', 50)->nullable();
                if ($afterType) $col->after('type');
            }

            // ip
            if (!Schema::hasColumn('audit_logs', 'ip')) {
                $col = $table->string('ip', 60)->nullable();
                if ($afterModel) $col->after('model_id');
            }

            // user_agent
            if (!Schema::hasColumn('audit_logs', 'user_agent')) {
                $col = $table->text('user_agent')->nullable();
                if ($afterIp) $col->after('ip');
            }

            // meta
            if (!Schema::hasColumn('audit_logs', 'meta')) {
                $col = $table->text('meta')->nullable();
                if ($afterUA) $col->after('user_agent');
            }
        });
    }

    public function down(): void {}
};
