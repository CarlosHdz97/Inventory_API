<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccesoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accesory', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->mediumInteger('existencia');
            $table->mediumInteger('stockMin');
            $table->mediumInteger('stockMax');
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
        Schema::dropIfExists('accesory');
    }
}
