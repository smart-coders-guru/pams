<?php

use Illuminate\Database\Seeder;

class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('apps')->insert([
           'apps_key' => 'TestKey',
           'apps_name' => 'Admin',
           'apps_login' => 'noApp',
           'apps_password' => 'noApp',
           'apps_desc' => 'Ce compte est reservé à l\'administrateur de cette plateforme',
           'apps_creation_date' => '2018-10-05 10:05:15',
           'apps_state' => 0,
       ]);
    }
}
