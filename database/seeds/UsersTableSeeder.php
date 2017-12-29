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
        DB::table('users')->delete();
        DB::table('users')->insert(
            [
                ['name' => 'Admin', 'username' => 'admin', 'email' => 'awitenfest@gmail.com', 'password' => bcrypt('1234'), 'role' => 'admin', 'confirmed' => true],
                ['name' => 'Promotor1', 'username' => 'promotor1', 'email' => 'prm1@gmail.com', 'password' => bcrypt('1234'), 'role' => 'promoter', 'confirmed' => true],
                ['name' => 'Promotor2', 'username' => 'promotor2', 'email' => 'prm2@gmail.com', 'password' => bcrypt('1234'), 'role' => 'promoter', 'confirmed' => true],
                ['name' => 'Manager1', 'username' => 'manager1', 'email' => 'mngr1@gmail.com', 'password' => bcrypt('1234'), 'role' => 'manager', 'confirmed' => true],
                ['name' => 'Manager2', 'username' => 'manager2', 'email' => 'mngr2@gmail.com', 'password' => bcrypt('1234'), 'role' => 'manager', 'confirmed' => true],
            ]
        );
    }
}
