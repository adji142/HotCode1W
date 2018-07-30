<?php

use Illuminate\Database\Seeder;

class NumeratorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cek1 = DB::table('mstr.numerator')->where('doc','=','KOREKSI_RETUR_PEMBELIAN')->exists();
        if(!$cek1) {
            DB::table('mstr.numerator')->insert([
                [
                    'id'    => 99,
                    'doc'   => 'KOREKSI_RETUR_PEMBELIAN',
                    'depan' => 'KRB',
                    'lebar' => 7,
                    'nomor' => 1315,
                ],
            ]);
        }

        $cek2 = DB::table('mstr.appsetting')->where('recordownerid','=','3')->where('keyid','=','ppn')->exists();
        if(!$cek2) {
            DB::table('mstr.appsetting')->insert([
                [
                    'recordownerid' => 3,
                    'keyid' => 'ppn',
                    'value' => 10,
                ],
            ]);
        }
    }
}
