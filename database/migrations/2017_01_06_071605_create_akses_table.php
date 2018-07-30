<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAksesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secure.subcabanguser', function (Blueprint $table) {
            $table->unsignedInteger('sub_cabang_id');
            $table->unsignedInteger('user_id');

            $table->foreign('sub_cabang_id')->references('id')->on('mstr.subcabang')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('secure.users')->onDelete('cascade');

            $table->primary(['sub_cabang_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('secure.subcabanguser');
    }
}
