<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sahams', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('nama_saham');
            $table->string('open');
            $table->string('high');
            $table->string('low');
            $table->string('close');
            $table->string('volume');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sahams');
    }
};
