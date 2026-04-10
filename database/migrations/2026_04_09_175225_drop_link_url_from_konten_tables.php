<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('katalog_produk', function (Blueprint $table) {
            if (Schema::hasColumn('katalog_produk', 'link_url')) {
                $table->dropColumn('link_url');
            }
        });

        Schema::table('tips_trik', function (Blueprint $table) {
            if (Schema::hasColumn('tips_trik', 'link_url')) {
                $table->dropColumn('link_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('katalog_produk', function (Blueprint $table) {
            if (!Schema::hasColumn('katalog_produk', 'link_url')) {
                $table->string('link_url')->nullable()->after('gambar');
            }
        });

        Schema::table('tips_trik', function (Blueprint $table) {
            if (!Schema::hasColumn('tips_trik', 'link_url')) {
                $table->string('link_url')->nullable()->after('gambar');
            }
        });
    }
};
