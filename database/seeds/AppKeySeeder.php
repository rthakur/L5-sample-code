<?php

use Illuminate\Database\Seeder;
use App\Models\ApiKey;

class AppKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ApiKey::truncate();

        $api_keys = [
            ['api_key' => '123', 'secret_key' => '456']
        ];

        foreach ($api_keys as $key) {
            $api_key = new ApiKey();
            $api_key->api_key = $key['api_key'];
            $api_key->secret_key = $key['secret_key'];
            $api_key->save();
        }

    }
}
