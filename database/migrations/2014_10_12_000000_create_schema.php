<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Pacuna\Schemas\Facades\PGSchema;

class CreateSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // if(!PGSchema::schemaExists('secure')) {
        //     PGSchema::create('secure');
        // }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // if(PGSchema::schemaExists('secure')) {
        //     PGSchema::drop('secure');
        // }
    }
}
