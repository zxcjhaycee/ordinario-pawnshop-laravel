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
            ['charge_type' => 'discount', 'charge_name' => 'Discount 1', 'amount' => '500'],
            ['charge_type' => 'discount', 'charge_name' => 'Discount 2', 'amount' => '800'],
            ['charge_type' => 'charges', 'charge_name' => 'Charges 1', 'amount' => '300'],
            ['charge_type' => 'charges', 'charge_name' => 'Charges 2', 'amount' => '200'],
        ];
        \DB::table('other_charges')->insert($type);
    }
}
