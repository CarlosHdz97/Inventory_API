<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('name', '60');
            $table->string('surname', '30');
            $table->string('nickname', '30');
            $table->string('email', '60')->nullable();
            $table->enum('rol', ['Administrador', 'Bodeguero', 'Vendedor']);
            $table->text('password')->nullable();
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
        Schema::dropIfExists('user');
    }
}
