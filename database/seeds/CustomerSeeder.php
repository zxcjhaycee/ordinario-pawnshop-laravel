<?php

use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(App\Customer::class, 10)->create();
        $customer = \App\Customer::all();
        $attachment = array();
        foreach($customer as $key => $value){
            $attachment = ['customer_id' => $value->id, 'attachment_id' => rand(1, 3), 'number' => '123123123', 'path' => 'test'];
            \DB::table('customers_attachments')->insert($attachment);

        }

    }
}
