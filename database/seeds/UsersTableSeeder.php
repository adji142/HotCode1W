<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('secure.users')->insert([
        //     [
        //         'username' => 'manager',
        //         'name'     => 'Manager',
        //         'email'    => 'manager_sas@sas.com',
        //         'password' => bcrypt('manager'),
        //     ],
        // ]);
        DB::table("secure.users")->insert([
            [
                'username' => 'manager',
                'name'     => 'Manager',
                'email'    => 'manager_sas@sas.com',
                'password' => bcrypt('manager'),
                'karyawanid' => null,
            ],
            [
                'username' => 'penj',
                'name'     => 'penj',
                'email'    => null,
                'password' => bcrypt('penj'),
                'karyawanid' => null,
            ],
            [
                'username' => 'javasign',
                'name'     => 'javasign',
                'email'    => null,
                'password' => bcrypt('javasign'),
                'karyawanid' => null,
            ],
            [
                'username' => 'ind',
                'name'     => 'indra',
                'email'    => null,
                'password' => bcrypt('ind'),
                'karyawanid' => 59,
            ],
            [
                'username' => 'autowebsynch',
                'name'     => 'autowebsynch',
                'email'    => null,
                'password' => bcrypt('autowebsynch'),
                'karyawanid' => null,
            ],
            [
                'username' => 'sgt',
                'name'     => 'sigit',
                'email'    => null,
                'password' => bcrypt('sgt'),
                'karyawanid' => 1035,
            ],
            [
                'username' => 'dit',
                'name'     => 'dodit',
                'email'    => null,
                'password' => bcrypt('dit'),
                'karyawanid' => 172,
            ],
            [
                'username' => 'okt',
                'name'     => 'oktafian',
                'email'    => null,
                'password' => bcrypt('okt'),
                'karyawanid' => 48,
            ],
            [
                'username' => 'eri',
                'name'     => 'eri',
                'email'    => null,
                'password' => bcrypt('eri'),
                'karyawanid' => 888,
            ],
            [
                'username' => 'gun',
                'name'     => 'gunawan',
                'email'    => null,
                'password' => bcrypt('gun'),
                'karyawanid' => 976,
            ],
            [
                'username' => 'jul',
                'name'     => 'djulia',
                'email'    => null,
                'password' => bcrypt('jul'),
                'karyawanid' => 107,
            ],
            [
                'username' => 'risma',
                'name'     => 'risma',
                'email'    => null,
                'password' => bcrypt('risma'),
                'karyawanid' => 982,
            ],
            [
                'username' => 'marina',
                'name'     => 'marina',
                'email'    => null,
                'password' => bcrypt('marina'),
                'karyawanid' => 992,
            ],
            [
                'username' => 'rena',
                'name'     => 'rena',
                'email'    => null,
                'password' => bcrypt('rena'),
                'karyawanid' => 2,
            ],
            [
                'username' => 'wir',
                'name'     => 'wiratno',
                'email'    => null,
                'password' => bcrypt('wir'),
                'karyawanid' => 12,
            ],
            [
                'username' => 'widi',
                'name'     => 'widi',
                'email'    => null,
                'password' => bcrypt('widi'),
                'karyawanid' => 1017,
            ],
            [
                'username' => 'tri',
                'name'     => 'tri',
                'email'    => null,
                'password' => bcrypt('tri'),
                'karyawanid' => 921,
            ],
            [
                'username' => 'catur',
                'name'     => 'catur',
                'email'    => null,
                'password' => bcrypt('catur'),
                'karyawanid' => 967,
            ],
            [
                'username' => 'rohmanto',
                'name'     => 'rohmanto',
                'email'    => null,
                'password' => bcrypt('rohmanto'),
                'karyawanid' => 1135,
            ],
            [
                'username' => 'UAT',
                'name'     => 'UAT',
                'email'    => null,
                'password' => bcrypt('UAT'),
                'karyawanid' => 1035,
            ],
        ]);
    }
}
