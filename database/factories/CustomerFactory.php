<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Customer;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        //
        'first_name' => $faker->firstName,
        'middle_name' => "",
        'last_name' => $faker->lastName,
        'suffix' => $faker->suffix,
        'sex' => 'male',
        'birthdate' => $faker->date,
        'civil_status' => 'single',
        'email' => $faker->email,
        'contact_number' => '090909090909',
        'alternate_number' => '090909090909',
        'present_address' => $faker->address,
        'present_address_two' => $faker->address,
        'present_address_two' => $faker->address,
        'present_area' => $faker->city,
        'present_city' => $faker->city,
        'present_zip_code' => '1008',
        'permanent_address' => $faker->address,
        'permanent_address_two' => $faker->address,
        'permanent_address_two' => $faker->address,
        'permanent_area' => $faker->city,
        'permanent_city' => $faker->city,
        'permanent_zip_code' => '1008'

        
    ];
});
