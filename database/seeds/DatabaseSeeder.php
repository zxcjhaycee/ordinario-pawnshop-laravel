<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            ItemRateSeeder::class,
            BranchSeeder::class,
            CustomerSeeder::class,
            AttachmentSeeder::class,
            OtherChargesSeeder::class,
        ]);
    }
}
