<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Role::truncate();
    
      $roles = [
        ['name' => 'Admin'],
        ['name' => 'Regular User'],
        ['name' => 'Agent'],
    		['name' => 'Agency'],
    		['name' => 'Personal Admin']
      ];	 
      foreach ($roles as $role) {
        $newRole = new Role;
        $newRole->name = $role['name'];
        $newRole->save();
      }
    }
}
