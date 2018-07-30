<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('secure.roles')->insert([
            [ 'groupapps' => 'TRADING', 'name' => "manager"],
            [ 'groupapps' => 'TRADING', 'name' => "UAT"],
            [ 'groupapps' => 'TRADING', 'name' => "piutang.user"],
            [ 'groupapps' => 'TRADING', 'name' => "piutang.supervisor"],
            [ 'groupapps' => 'TRADING', 'name' => "gudang.user"],
            [ 'groupapps' => 'TRADING', 'name' => "gudang.supervisor"],
            [ 'groupapps' => 'TRADING', 'name' => "akunting.user"],
            [ 'groupapps' => 'TRADING', 'name' => "akunting.supervisor"],
            [ 'groupapps' => 'TRADING', 'name' => "expedisi.user"],
            [ 'groupapps' => 'TRADING', 'name' => "expedisi.supervisor"],
            [ 'groupapps' => 'TRADING', 'name' => "penjualan.retur"],
            [ 'groupapps' => 'TRADING', 'name' => "penjualan.nota"],
            [ 'groupapps' => 'TRADING', 'name' => "penjualan.pickinglist"],
            [ 'groupapps' => 'TRADING', 'name' => "synch"],
            [ 'groupapps' => 'TRADING', 'name' => "penjualan.supervisor"],
            [ 'groupapps' => 'FINANCE', 'name' => "manager" ],
        ]);
    }
}
