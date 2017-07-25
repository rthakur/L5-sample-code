<?php

use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    
    # 0 => not included
    # -1 => Unlimited
    # -2 => Optional
    
    public function run()
    {
      Package::truncate();
      $packages = [
        [
            'name' => 'Free', 'price' => '0', 'price_yearly' => '0', 
            'currency'=> 'USD', 'objects' => '0', 'agent_profiles' => '1', 
            'agency_profiles' => '1', 'logotype' => '1', 'agent_accounts' => '0', 
            'generic_statistics'=> '0', 'api_access' => '0', 'synchronization' => '0', 
            'search_list_presentation' => '0', 'first_on_search_list' => '0','view_status' => '1'
        ],[
            'name' => 'Professional', 'price' => '10', 'price_yearly' => '0', 
            'currency'=> 'USD', 'objects' => '-1', 'agent_profiles' => '1', 
            'agency_profiles' => '1', 'logotype' => '1', 'agent_accounts' => '1', 
            'generic_statistics'=> '1', 'api_access' => '-2', 'synchronization' => '-2', 
            'search_list_presentation' => '0', 'first_on_search_list' => '0','view_status' => '1' 
        ],[
            'name' => 'Professional with API', 'price' => '20', 'price_yearly' => '0', 
            'currency'=> 'USD', 'objects' => '-1', 'agent_profiles' => '1', 
            'agency_profiles' => '1', 'logotype' => '1', 'agent_accounts' => '1', 
            'generic_statistics'=> '1', 'api_access' => '-2', 'synchronization' => '-2', 
            'search_list_presentation' => '0', 'first_on_search_list' => '0','view_status' => '0' 
        ], 
        [
            'name' => 'Professional with Syncronize', 'price' => '30', 'price_yearly' => '0', 
            'currency'=> 'USD', 'objects' => '-1', 'agent_profiles' => '1', 
            'agency_profiles' => '1', 'logotype' => '1', 'agent_accounts' => '1', 
            'generic_statistics'=> '1', 'api_access' => '-2', 'synchronization' => '-2', 
            'search_list_presentation' => '0', 'first_on_search_list' => '0','view_status' => '0' 
        ],
        [
            'name' => 'Campaign', 'price' => '0', 'price_yearly' => '0', 
            'currency'=> 'USD', 'objects' => '-1', 'agent_profiles' => '1', 
            'agency_profiles' => '1', 'logotype' => '1', 'agent_accounts' => '1', 
            'generic_statistics'=> '1', 'api_access' => '-2', 'synchronization' => '-2', 
            'search_list_presentation' => '0', 'first_on_search_list' => '0','view_status' => '0' 
        ], 
        
      ];
      
      foreach($packages as $package)
      {  
        $newPackage = Package::firstOrCreate($package);
        $newPackage->save();
      }
      echo "\n Done! \n";
    }
}
