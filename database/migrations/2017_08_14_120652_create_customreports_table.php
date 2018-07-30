<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomreportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report.customreport', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customreportgroupid');
            $table->string('nama');
            $table->text('query');
            $table->text('param');
            $table->text('field');
            $table->boolean('aktif');
            $table->string('createdby')->nullable();
            $table->string('lastupdatedby')->nullable();
            $table->timestamp('createdon')->nullable();
            $table->timestamp('lastupdatedon')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('report.customreport');
    }
}
