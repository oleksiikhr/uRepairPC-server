<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestPrioritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_priorities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('color', 10)->nullable();
            $table->unsignedTinyInteger('value')->default(1);
            $table->text('description')->nullable();
            $table->boolean('default')->default(false);
            $table->softDeletes();
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
        Schema::dropIfExists('request_priorities');
    }
}
