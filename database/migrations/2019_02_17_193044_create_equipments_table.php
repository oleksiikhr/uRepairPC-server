<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->nullable();
            $table->string('inventory_number')->nullable();
            $table->unsignedInteger('type_id');
            $table->unsignedInteger('manufacturer_id')->nullable();
            $table->unsignedInteger('model_id')->nullable();
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('equipment_types')
                ->onDelete('cascade');
            $table->foreign('manufacturer_id')->references('id')->on('equipment_manufacturers')
                ->onDelete('set null');
            $table->foreign('model_id')->references('id')->on('equipment_models')
                ->onDelete('set null');

            $table->index('manufacturer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipments');
    }
}
