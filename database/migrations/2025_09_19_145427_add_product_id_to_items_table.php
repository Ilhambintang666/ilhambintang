<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductIdToItemsTable extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            // tambahkan kolom product_id nullable (jika ingin terhubung ke products)
            $table->foreignId('product_id')->nullable()->after('id')->constrained('products')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_id');
        });
    }
}
