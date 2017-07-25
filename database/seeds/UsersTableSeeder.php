<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $userRoles = [ 'admin', 'regularuser', 'agent', 'agency', 'vaadmin'];
      
      foreach($userRoles as $key => $newUser)
      {  
        $role = $key+1;
        $user = User::firstorNew(['email' => $newUser.'@miparo.com']);
        $user->name = $newUser;
        $user->password = bcrypt('123456');
        $user->role_id = $role; //admin type user
        $user->save();
      }
    }
}
