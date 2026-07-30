<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('prospects', 'referral_user_id')) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            $foreignKeys = DB::select(
                <<<'SQL'
                    SELECT CONSTRAINT_NAME AS constraint_name
                    FROM information_schema.KEY_COLUMN_USAGE
                    WHERE TABLE_SCHEMA = ?
                      AND TABLE_NAME = 'prospects'
                      AND COLUMN_NAME = 'referral_user_id'
                      AND REFERENCED_TABLE_NAME IS NOT NULL
                SQL,
                [DB::getDatabaseName()]
            );

            foreach ($foreignKeys as $foreignKey) {
                $constraint = str_replace('`', '``', (string) $foreignKey->constraint_name);
                DB::statement("ALTER TABLE `prospects` DROP FOREIGN KEY `{$constraint}`");
            }

            DB::statement(
                'ALTER TABLE `prospects` MODIFY `referral_user_id` VARCHAR(150) NULL'
            );

            return;
        }

        Schema::table('prospects', function (Blueprint $table) {
            $table->string('referral_user_id', 150)->nullable()->change();
        });
    }

    public function down(): void
    {
        // String employee IDs cannot safely be converted back to numeric user IDs.
    }
};
