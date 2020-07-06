<?php

use Illuminate\Database\Seeder;

class OtherChargesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $type = [
            ['charge_type' => 'Bank Charges', 'amount' => '20000'],
            ['charge_type' => 'Extra Charges', 'amount' => '30000'],
        ];
        \DB::table('other_charges')->insert($type);
    }
}
