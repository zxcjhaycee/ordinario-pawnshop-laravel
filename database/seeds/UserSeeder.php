<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(App\User::class, 1)->create();
            $faker = \Faker\Factory::create();
            $user = array(
                array(
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'username' => 'admin',
                    'password' => '$2y$10$F5Iu0fbep2q92g./Q52b7uQukmjbpdnM/2iop6GbjLTO6S5sZd/VO',
                    'branch_id' => rand(1, 3),
                    'access' => 'Administrator',
                    // 'auth_code' => rand(1000, 9999)
                    'auth_code' => '1111'
                ),
                array(
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'username' => 'manager',
                    'password' => '$2y$10$7sMiaTt4/qr29XjZG46oK.RjIdsIxMM/DFKTn3blLKotpz3l3xRUK',
                    'branch_id' => rand(1, 3),
                    'access' => 'Manager',
                    // 'auth_code' => rand(1000, 9999)
                    'auth_code' => '2222'
                ),
                array(
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'username' => 'staff',
                    'password' => '$2y$10$PZWBS1b5FQBq6C.7NpoUn.OWIwEMCoMt.IM3vtk32nhESdIOrlDVC',
                    'branch_id' => rand(1, 3),
                    'access' => 'Staff',
                    // 'auth_code' => rand(1000, 9999)
                    'auth_code' => '3333'
                ),
            );
            foreach($user as $users){
                factory(App\User::class)->create($users);
            }

    }
}
