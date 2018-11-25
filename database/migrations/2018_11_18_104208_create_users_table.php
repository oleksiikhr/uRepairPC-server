<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('login');
            $table->string('password');
            $table->tinyInteger('role')->default(0);
            $table->string('socket_token')->nullable();
            $table->text('secret')->nullable();
            $table->string('image')->nullable();
            $table->dateTime('last_seen')->nullable();
            $table->boolean('is_blocked')->default(0);
            $table->timestamps();
        });

        // TODO Remove, quick example -> seeder*
        App\User::create([
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'login' => 'admin',
            'password' => Illuminate\Support\Facades\Hash::make('admin123'),
            'secret' => 'secret123',
            'socket_token' => Str::random(60),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
