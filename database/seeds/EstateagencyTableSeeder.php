<?php

use Illuminate\Database\Seeder;
use App\Models\Estateagency;

class EstateagencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Estateagency::truncate();
        
        $estateagencies = [
          [
            'public_name' => 'Genius Properties',
            'legal_companyname' => 'Genius Properties',
            'info_email' => 'agency@example.com',
            'country_id' => '1',
            'logo' => '/upload/agency/genius-properties.png',
          ],
          
          [
            'public_name' => 'House Trusted',
            'legal_companyname' => 'House Trusted',
            'info_email' => 'agency@example.com',
            'country_id' => '1',
            'logo' => '/upload/agency/house -trusted.png',
          ],
          
          [
            'public_name' => 'LightHouse Estate',
            'legal_companyname' => 'LightHouse Estate',
            'info_email' => 'agency@example.com',
            'country_id' => '1',
            'logo' => '/upload/agency/lighthouse-estate.png',
          ],
        ];
        
        foreach($estateagencies as $estateagency)
        {
          $newEstateagency = new Estateagency;
          $newEstateagency->public_name = $estateagency['public_name'];
          $newEstateagency->legal_companyname = $estateagency['legal_companyname'];
          $newEstateagency->info_email = $estateagency['info_email'];
          $newEstateagency->logo = $estateagency['logo'];
          $newEstateagency->country_id = $estateagency['country_id'];
          $newEstateagency->save();
        }
    }
}
