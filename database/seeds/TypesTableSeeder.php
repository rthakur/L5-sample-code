<?php

use Illuminate\Database\Seeder;
use App\Models\Type;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Type::truncate();
        
        $types = [
          ['type' => 'Owner'],
          ['type' => 'Agent'],
          ['type' => 'Assistant'],
          ['type' => 'Photograp'],      
          ['type' => 'Stylist'],      
        ];
        
        foreach($types as $type)
        {
          $newType = new Type;
          $newType->type = $type['type'];
          $newType->save();
        }
    }
}
