<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->string('kode_barang', 30)->unique()->nullable()->after('id');
            $table->unsignedInteger('stok_minimum')->default(5)->after('stok');
        });
    }

    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn(['kode_barang', 'stok_minimum']);
        });
    }
};
