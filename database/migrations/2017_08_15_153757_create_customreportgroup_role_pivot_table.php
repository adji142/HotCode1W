<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomreportgroupRolePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secure.customreportgrouprole', function (Blueprint $table) {
            $table->integer('customreportgroup_id')->unsigned()->index();
            $table->foreign('customreportgroup_id')->references('id')->on('report.customreportgroup')->onDelete('cascade');
            $table->integer('role_id')->unsigned()->index();
            $table->foreign('role_id')->references('id')->on('secure.roles')->onDelete('cascade');
            $table->primary(['customreportgroup_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('secure.customreportgrouprole');
    }
}
