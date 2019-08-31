<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedMediumInteger('responsable_id');
            $table->string('entrego', 100);
            $table->string('notes',200);
            $table->string('costo',200);
            $table->smallInteger('quantity');
            $table->enum('action', ['Registro', 'Devuelto', 'Asignado', 'En reparación', 'Perdido']);
            $table->text('accesorios');
            $table->date('fecha');
            $table->string('sucursal',50);
            $table->integer('historical_id');
            $table->string('historical_type', 30);
            $table->foreign('responsable_id', 100)->references("id")->on('user');
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
        Schema::dropIfExists('history');
    }
}
