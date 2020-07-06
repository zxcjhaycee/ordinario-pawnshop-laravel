<?php

use Illuminate\Database\Seeder;

class AttachmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // factory(App\Attachment::class, 3)->create();
        $type = [
            ['type' => 'Police Clearace'],
            ['type' => 'SSS / UMID'],
            ['type' => 'Postal ID'],
        ];
        \DB::table('attachments')->insert($type);



    }
}
