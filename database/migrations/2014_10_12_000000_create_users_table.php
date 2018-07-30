<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secure.users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('active',1)->default(0);
            $table->string('createdby')->nullable();
            $table->string('updatedby')->nullable();
            $table->timestamps();
            $table->text('foto')->nullable();
            $table->integer('karyawanid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('secure.users');
    }
}
