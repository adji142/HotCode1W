<?php

use Illuminate\Database\Seeder;

class RoleuserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Catch user first
        $u = App\Models\User::pluck("id","username");

        // Add a user to a role
        $r_manager = App\Models\Role::where("name", "manager")->where("groupapps","TRADING")->first();
        $r_manager->user()->attach([
            $u['manager'],
        ]);

        $r_uat = App\Models\Role::where("name", "UAT")->where("groupapps","TRADING")->first();
        $r_uat->user()->attach([
            $u['UAT'],
        ]);

        $r_piutanguser = App\Models\Role::where("name", "piutang.user")->where("groupapps","TRADING")->first();
        $r_piutanguser->user()->attach([
            $u['risma'],
            $u['marina'],
        ]);

        $r_piutangsupervisor = App\Models\Role::where("name", "piutang.supervisor")->where("groupapps","TRADING")->first();
        $r_piutangsupervisor->user()->attach([
            $u['jul'],
        ]);

        $r_synch = App\Models\Role::where("name", "synch")->where("groupapps","TRADING")->first();
        $r_synch->user()->attach([
            $u['autowebsynch'],
        ]);

        $r_penjualansupervisor = App\Models\Role::where("name", "penjualan.supervisor")->where("groupapps","TRADING")->first();
        $r_penjualansupervisor->user()->attach([
            $u['penj'],
            $u['javasign'],
            $u['eri'],
            $u['gun'],
            $u['ind'],
        ]);

        // $role_manager_financial  = App\Models\Role::where('name', 'manager')->where('groupapps','FINANCE')->first();
        // $role_manager_financial->user()->attach($u);     
    }
}
