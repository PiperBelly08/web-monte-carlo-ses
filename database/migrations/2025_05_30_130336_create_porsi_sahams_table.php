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
        Schema::create('porsi_sahams', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('nama_saham');
            $table->string('close');
            $table->decimal('porsi',10, 4);
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
        Schema::dropIfExists('porsi_sahams');
    }
};
