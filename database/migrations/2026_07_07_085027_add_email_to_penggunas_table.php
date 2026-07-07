<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom email ke tabel penggunas.
     * Email digunakan untuk notifikasi stok kritis.
     */
    public function up(): void
    {
        Schema::table('penggunas', function (Blueprint $table) {
            $table->string('email', 100)->nullable()->after('username');
        });
    }

    public function down(): void
    {
        Schema::table('penggunas', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
};
