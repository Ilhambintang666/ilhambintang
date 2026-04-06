<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // true  = barang bisa dipinjam oleh user
            // false = barang paten (inventaris tetap, tidak bisa dipinjam)
            $table->boolean('is_loanable')->default(true)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('is_loanable');
        });
    }
};
