<?php

use Illuminate\Database\Seeder;

class ItemRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('rates')->delete();
        \DB::table('item_types')->delete();
        \DB::table('item_categories')->delete();
        \DB::table('branches')->delete();



        $item_categories = ['JEWELRY', 'NON-JEWELRY'];
        foreach($item_categories as $item_category)
        {
            factory(App\Item_category::class)->create(['item_category' => $item_category]);
        }
        $ics = App\Item_category::all();
        foreach ($ics as $ic)
        {
            if ($ic->item_category === 'JEWELRY') {
                $item_types = ['GOLD', 'SILVER', 'PLATINUM'];
                foreach($item_types as $item_type)
                {
                    factory(App\Item_type::class)->create([
                        'item_category_id' => $ic->id,
                        'item_type' => $item_type
                    ]);
                }
            } else {
                factory(App\Item_type::class)->create([
                    'item_category_id' => $ic->id,
                    'item_type' => 'OTHER ITEMS'
                ]);
            }
        }
        factory(App\Branch::class, 3)->create()->each(function($branch){
            $its = App\Item_type::all();
            foreach ($its as $it)
            {
                if($it->item_type !== 'OTHER ITEMS') {
                    $karats = [24, 20, 18, 16];
                    foreach($karats as $karat)
                    {
                        factory(App\Rate::class)->create([
                            'branch_id' => $branch->id,
                            'item_type_id' => $it->id,
                            'karat' => $karat
                        ]);
                    }
                }
            }
        });
    }
}
