<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('mobile', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('modelo', '30');
            $table->string('serie', '30');
            $table->string('emei', '30');
            $table->text('accesorios');
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
        Schema::dropIfExists('mobile');
    }
}
