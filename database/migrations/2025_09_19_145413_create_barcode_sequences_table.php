<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarcodeSequencesTable extends Migration
{
    public function up()
    {
        Schema::create('barcode_sequences', function (Blueprint $table) {
            $table->id();
            $table->string('prefix')->unique();
            $table->unsignedBigInteger('last_number')->default(0);
            $table->unsignedSmallInteger('digits')->default(4);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('barcode_sequences');
    }
}
