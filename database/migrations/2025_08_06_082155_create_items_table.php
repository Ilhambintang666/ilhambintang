<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('items', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('barcode')->unique();
        $table->text('description')->nullable();
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->foreignId('location_id')->constrained()->onDelete('cascade');
        $table->enum('condition', ['baik', 'rusak', 'dalam_perbaikan']);
        $table->enum('status', ['tersedia', 'dipinjam', 'maintenance']);
        $table->integer('quantity')->default(1);
        $table->date('purchase_date')->nullable();
        $table->decimal('price', 10, 2)->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
