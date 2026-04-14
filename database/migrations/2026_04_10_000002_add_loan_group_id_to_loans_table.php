<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            // nullable → data lama tidak rusak
            $table->foreignId('loan_group_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('loan_groups')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign(['loan_group_id']);
            $table->dropColumn('loan_group_id');
        });
    }
};
