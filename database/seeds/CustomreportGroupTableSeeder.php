<?php

use Illuminate\Database\Seeder;

class CustomreportGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('report.customreportgroup')->insert([
            ["nama" => "Penjualan"],
            ["nama" => "Piutang"],
            ["nama" => "Stock"],
            ["nama" => "Keuangan"],
            ["nama" => "Akunting"],
            ["nama" => "Semua"],
        ]);
    }
}
