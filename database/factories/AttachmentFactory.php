<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Attachment;
use Faker\Generator as Faker;

$factory->define(Attachment::class, function (Faker $faker) {
    $type = array('Police Cleareance', 'SSS / UMID', 'Postal ID');

    foreach($type as $key => $value){
        $result['type'][] = $value;
    }

    return $result;
});
