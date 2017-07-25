<?php

use Illuminate\Database\Seeder;
use App\Models\Texttype;

class TexttypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Texttype::truncate();
        
        $texttypes = [
          ['name' => 'Subject'],
          ['name' => 'Ingress'],
          ['name' => 'Description'],
          ['name' => 'Longtext'],
          ['name' => 'sss'],
        ];
        
        foreach($texttypes as $texttype)
        {
          $newtexttype = new Texttype;
          $newtexttype->name = $texttype['name'];
          $newtexttype->save();
        }
    }
}
