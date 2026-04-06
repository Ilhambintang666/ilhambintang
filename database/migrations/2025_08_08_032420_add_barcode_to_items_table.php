<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
   public function up(): void
{
    Schema::table('items', function (Blueprint $table) {
        if (!Schema::hasColumn('items', 'barcode')) {
            $table->string('barcode')->after('name');
        }
    });
}


    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Menghapus kolom barcode jika rollback
            $table->dropColumn('barcode');
        });
    }
};
