<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSyncColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Order Pembelian
        // Schema::table('pb.orderpembelian', function ($table) {
        //     $table->timestamp('sync11')->nullable();
        // });
        // Retur Pembelian
        // Schema::table('pb.returpembelian', function ($table) {
        //     $table->timestamp('sync11')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Order Pembelian
        // Schema::table('pb.orderpembelian', function ($table) {
        //     $table->dropColumn('sync11');
        // });
        // Retur Pembelian
        // Schema::table('pb.returpembelian', function ($table) {
        //     $table->dropColumn('sync11');
        // });
    }
}
