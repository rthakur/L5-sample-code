<?php

use Illuminate\Database\Seeder;
use App\Models\Country;
use Illuminate\Support\Facades\DB;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('set foreign_key_checks = 0;');
        Country::truncate();
        
        $countries = [
            ['name' => 'Andorra','language_id' => '1',],
            ['name' => 'United Arab Emirates','language_id' => '1',],
            ['name' => 'Afghanistan','language_id' => '1',],
            ['name' => 'Antigua and Barbuda','language_id' => '1',],
            ['name' => 'Anguilla','language_id' => '1',],
            ['name' => 'Albania','language_id' => '1',],
            ['name' => 'Armenia','language_id' => '1',],
            ['name' => 'Angola','language_id' => '1',],
            ['name' => 'Antarctica','language_id' => '1',],
            ['name' => 'Argentina','language_id' => '1',],
            ['name' => 'American Samoa','language_id' => '1',],
            ['name' => 'Austria','language_id' => '1',],
            ['name' => 'Australia','language_id' => '1',],
            ['name' => 'Aruba','language_id' => '1',],
            ['name' => 'Åland','language_id' => '1',],
            ['name' => 'Azerbaijan','language_id' => '1',],
            ['name' => 'Bosnia and Herzegovina','language_id' => '1',],
            ['name' => 'Barbados','language_id' => '1',],
            ['name' => 'Bangladesh','language_id' => '1',],
            ['name' => 'Belgium','language_id' => '1',],
            ['name' => 'Burkina Faso','language_id' => '1',],
            ['name' => 'Bulgaria','language_id' => '1',],
            ['name' => 'Bahrain','language_id' => '1',],
            ['name' => 'Burundi','language_id' => '1',],
            ['name' => 'Benin','language_id' => '1',],
            ['name' => 'Saint Barthélemy','language_id' => '1',],
            ['name' => 'Bermuda','language_id' => '1',],
            ['name' => 'Brunei','language_id' => '1',],
            ['name' => 'Bolivia','language_id' => '1',],
            ['name' => 'Bonaire','language_id' => '1',],
            ['name' => 'Brazil','language_id' => '1',],
            ['name' => 'Bahamas','language_id' => '1',],
            ['name' => 'Bhutan','language_id' => '1',],
            ['name' => 'Bouvet Island','language_id' => '1',],
            ['name' => 'Botswana','language_id' => '1',],
            ['name' => 'Belarus','language_id' => '1',],
            ['name' => 'Belize','language_id' => '1',],
            ['name' => 'Canada','language_id' => '1',],
            ['name' => 'Cocos [Keeling] Islands','language_id' => '1',],
            ['name' => 'Democratic Republic of the Congo','language_id' => '1',],
            ['name' => 'Central African Republic','language_id' => '1',],
            ['name' => 'Republic of the Congo','language_id' => '1',],
            ['name' => 'Switzerland','language_id' => '1',],
            ['name' => 'Ivory Coast','language_id' => '1',],
            ['name' => 'Cook Islands','language_id' => '1',],
            ['name' => 'Chile','language_id' => '1',],
            ['name' => 'Cameroon','language_id' => '1',],
            ['name' => 'China','language_id' => '1',],
            ['name' => 'Colombia','language_id' => '1',],
            ['name' => 'Costa Rica','language_id' => '1',],
            ['name' => 'Cuba','language_id' => '1',],
            ['name' => 'Cape Verde','language_id' => '1',],
            ['name' => 'Curacao','language_id' => '1',],
            ['name' => 'Christmas Island','language_id' => '1',],
            ['name' => 'Cyprus','language_id' => '1',],
            ['name' => 'Czechia','language_id' => '1',],
            ['name' => 'Germany','language_id' => '1',],
            ['name' => 'Djibouti','language_id' => '1',],
            ['name' => 'Denmark','language_id' => '1',],
            ['name' => 'Dominica','language_id' => '1',],
            ['name' => 'Dominican Republic','language_id' => '1',],
            ['name' => 'Algeria','language_id' => '1',],
            ['name' => 'Ecuador','language_id' => '1',],
            ['name' => 'Estonia','language_id' => '1',],
            ['name' => 'Egypt','language_id' => '1',],
            ['name' => 'Western Sahara','language_id' => '1',],
            ['name' => 'Eritrea','language_id' => '1',],
            ['name' => 'Spain','language_id' => '1',],
            ['name' => 'Ethiopia','language_id' => '1',],
            ['name' => 'Finland','language_id' => '1',],
            ['name' => 'Fiji','language_id' => '1',],
            ['name' => 'Falkland Islands','language_id' => '1',],
            ['name' => 'Micronesia','language_id' => '1',],
            ['name' => 'Faroe Islands','language_id' => '1',],
            ['name' => 'France','language_id' => '1',],
            ['name' => 'Gabon','language_id' => '1',],
            ['name' => 'United Kingdom','language_id' => '1',                            ],
            ['name' => 'Grenada','language_id' => '1',],
            ['name' => 'Georgia','language_id' => '1',],
            ['name' => 'French Guiana','language_id' => '1',],
            ['name' => 'Guernsey','language_id' => '1',],
            ['name' => 'Ghana','language_id' => '1',],
            ['name' => 'Gibraltar','language_id' => '1',],
            ['name' => 'Greenland','language_id' => '1',],
            ['name' => 'Gambia','language_id' => '1',],
            ['name' => 'Guinea','language_id' => '1',],
            ['name' => 'Guadeloupe','language_id' => '1',],
            ['name' => 'Equatorial Guinea','language_id' => '1',],
            ['name' => 'Greece','language_id' => '1',],
            ['name' => 'South Georgia and the South Sandwich Islands','language_id' => '1',],
            ['name' => 'Guatemala','language_id' => '1',],
            ['name' => 'Guam','language_id' => '1',],
            ['name' => 'Guinea-Bissau','language_id' => '1',],
            ['name' => 'Guyana','language_id' => '1',],
            ['name' => 'Hong Kong','language_id' => '1',],
            ['name' => 'Heard Island and McDonald Islands','language_id' => '1',],
            ['name' => 'Honduras','language_id' => '1',],
            ['name' => 'Croatia','language_id' => '1',],
            ['name' => 'Haiti','language_id' => '1',],
            ['name' => 'Hungary','language_id' => '1',],
            ['name' => 'Indonesia','language_id' => '1',],
            ['name' => 'Ireland','language_id' => '1',],
            ['name' => 'Israel','language_id' => '1',],
            ['name' => 'Isle of Man','language_id' => '1',],
            ['name' => 'India','language_id' => '1',],
            ['name' => 'British Indian Ocean Territory','language_id' => '1',],
            ['name' => 'Iraq','language_id' => '1',],
            ['name' => 'Iran','language_id' => '1',],
            ['name' => 'Iceland','language_id' => '1',],
            ['name' => 'Italy','language_id' => '1',],
            ['name' => 'Jersey','language_id' => '1',],
            ['name' => 'Jamaica','language_id' => '1',],
            ['name' => 'Jordan','language_id' => '1',],
            ['name' => 'Japan','language_id' => '1',],
            ['name' => 'Kenya','language_id' => '1',],
            ['name' => 'Kyrgyzstan','language_id' => '1',],
            ['name' => 'Cambodia','language_id' => '1',],
            ['name' => 'Kiribati','language_id' => '1',],
            ['name' => 'Comoros','language_id' => '1',],
            ['name' => 'Saint Kitts and Nevis','language_id' => '1',],
            ['name' => 'North Korea','language_id' => '1',],
            ['name' => 'South Korea','language_id' => '1',],
            ['name' => 'Kuwait','language_id' => '1',],
            ['name' => 'Cayman Islands','language_id' => '1',],
            ['name' => 'Kazakhstan','language_id' => '1',],
            ['name' => 'Laos','language_id' => '1',],
            ['name' => 'Lebanon','language_id' => '1',],
            ['name' => 'Saint Lucia','language_id' => '1',],
            ['name' => 'Liechtenstein','language_id' => '1',],
            ['name' => 'Sri Lanka','language_id' => '1',],
            ['name' => 'Liberia','language_id' => '1',],
            ['name' => 'Lesotho','language_id' => '1',],
            ['name' => 'Lithuania','language_id' => '1',],
            ['name' => 'Luxembourg','language_id' => '1',],
            ['name' => 'Latvia','language_id' => '1',],
            ['name' => 'Libya','language_id' => '1',],
            ['name' => 'Morocco','language_id' => '1',],
            ['name' => 'Monaco','language_id' => '1',],
            ['name' => 'Moldova','language_id' => '1',],
            ['name' => 'Montenegro','language_id' => '1',],
            ['name' => 'Saint Martin','language_id' => '1',],
            ['name' => 'Madagascar','language_id' => '1',],
            ['name' => 'Marshall Islands','language_id' => '1',],
            ['name' => 'Macedonia','language_id' => '1',],
            ['name' => 'Mali','language_id' => '1',],
            ['name' => 'Myanmar [Burma]','language_id' => '1',],
            ['name' => 'Mongolia','language_id' => '1',],
            ['name' => 'Macao','language_id' => '1',],
            ['name' => 'Northern Mariana Islands','language_id' => '1',],
            ['name' => 'Martinique','language_id' => '1',],
            ['name' => 'Mauritania','language_id' => '1',],
            ['name' => 'Montserrat','language_id' => '1',],
            ['name' => 'Malta','language_id' => '1',],
            ['name' => 'Mauritius','language_id' => '1',],
            ['name' => 'Maldives','language_id' => '1',],
            ['name' => 'Malawi','language_id' => '1',],
            ['name' => 'Mexico','language_id' => '1',],
            ['name' => 'Malaysia','language_id' => '1',],
            ['name' => 'Mozambique','language_id' => '1',],
            ['name' => 'Namibia','language_id' => '1',],
            ['name' => 'New Caledonia','language_id' => '1',],
            ['name' => 'Niger','language_id' => '1',],
            ['name' => 'Norfolk Island','language_id' => '1',],
            ['name' => 'Nigeria','language_id' => '1',],
            ['name' => 'Nicaragua','language_id' => '1',],
            ['name' => 'Netherlands','language_id' => '1',],
            ['name' => 'Norway','language_id' => '1',],
            ['name' => 'Nepal','language_id' => '1',],
            ['name' => 'Nauru','language_id' => '1',],
            ['name' => 'Niue','language_id' => '1',],
            ['name' => 'New Zealand','language_id' => '1',],
            ['name' => 'Oman','language_id' => '1',],
            ['name' => 'Panama','language_id' => '1',],
            ['name' => 'Peru','language_id' => '1',],
            ['name' => 'French Polynesia','language_id' => '1',],
            ['name' => 'Papua New Guinea','language_id' => '1',],
            ['name' => 'Philippines','language_id' => '1',],
            ['name' => 'Pakistan','language_id' => '1',],
            ['name' => 'Poland','language_id' => '1',],
            ['name' => 'Saint Pierre and Miquelon','language_id' => '1',],
            ['name' => 'Pitcairn Islands','language_id' => '1',],
            ['name' => 'Puerto Rico','language_id' => '1',],
            ['name' => 'Palestine','language_id' => '1',],
            ['name' => 'Portugal','language_id' => '1',],
            ['name' => 'Palau','language_id' => '1',],
            ['name' => 'Paraguay','language_id' => '1',],
            ['name' => 'Qatar','language_id' => '1',],
            ['name' => 'Réunion','language_id' => '1',],
            ['name' => 'Romania','language_id' => '1',],
            ['name' => 'Serbia','language_id' => '1',],
            ['name' => 'Russia','language_id' => '1',],
            ['name' => 'Rwanda','language_id' => '1',],
            ['name' => 'Saudi Arabia','language_id' => '1',],
            ['name' => 'Solomon Islands','language_id' => '1',],
            ['name' => 'Seychelles','language_id' => '1',],
            ['name' => 'Sudan','language_id' => '1',],
            ['name' => 'Sweden','language_id' => '1',],
            ['name' => 'Singapore','language_id' => '1',],
            ['name' => 'Saint Helena','language_id' => '1',],
            ['name' => 'Slovenia','language_id' => '1',],
            ['name' => 'Svalbard and Jan Mayen','language_id' => '1',],
            ['name' => 'Slovakia','language_id' => '1',],
            ['name' => 'Sierra Leone','language_id' => '1',],
            ['name' => 'San Marino','language_id' => '1',],
            ['name' => 'Senegal','language_id' => '1',],
            ['name' => 'Somalia','language_id' => '1',],
            ['name' => 'Suriname','language_id' => '1',],
            ['name' => 'South Sudan','language_id' => '1',],
            ['name' => 'São Tomé and Príncipe','language_id' => '1',],
            ['name' => 'El Salvador','language_id' => '1',],
            ['name' => 'Sint Maarten','language_id' => '1',],
            ['name' => 'Syria','language_id' => '1',],
            ['name' => 'Swaziland','language_id' => '1',],
            ['name' => 'Turks and Caicos Islands','language_id' => '1',],
            ['name' => 'Chad','language_id' => '1',],
            ['name' => 'French Southern Territories','language_id' => '1',],
            ['name' => 'Togo','language_id' => '1',],
            ['name' => 'Thailand','language_id' => '1',],
            ['name' => 'Tajikistan','language_id' => '1',],
            ['name' => 'Tokelau','language_id' => '1',],
            ['name' => 'East Timor','language_id' => '1',],
            ['name' => 'Turkmenistan','language_id' => '1',],
            ['name' => 'Tunisia','language_id' => '1',],
            ['name' => 'Tonga','language_id' => '1',],
            ['name' => 'Turkey','language_id' => '1',],
            ['name' => 'Trinidad and Tobago','language_id' => '1',],
            ['name' => 'Tuvalu','language_id' => '1',],
            ['name' => 'Taiwan','language_id' => '1',],
            ['name' => 'Tanzania','language_id' => '1',],
            ['name' => 'Ukraine','language_id' => '1',],
            ['name' => 'Uganda','language_id' => '1',],
            ['name' => 'U.S. Minor Outlying Islands','language_id' => '1',],
            ['name' => 'United States','language_id' => '1',],
            ['name' => 'Uruguay','language_id' => '1',],
            ['name' => 'Uzbekistan','language_id' => '1',],
            ['name' => 'Vatican City','language_id' => '1',],
            ['name' => 'Saint Vincent and the Grenadines','language_id' => '1',],
            ['name' => 'Venezuela','language_id' => '1',],
            ['name' => 'British Virgin Islands','language_id' => '1',],
            ['name' => 'U.S. Virgin Islands','language_id' => '1',],
            ['name' => 'Vietnam','language_id' => '1',],
            ['name' => 'Vanuatu','language_id' => '1',],
            ['name' => 'Wallis and Futuna','language_id' => '1',],
            ['name' => 'Samoa','language_id' => '1',],
            ['name' => 'Kosovo','language_id' => '1',],
            ['name' => 'Yemen','language_id' => '1',],
            ['name' => 'Mayotte','language_id' => '1',],
            ['name' => 'South Africa','language_id' => '1',],
            ['name' => 'Zambia','language_id' => '1',],
            ['name' => 'Zimbabwe','language_id' => '1',]
        ];
        
        foreach ($countries as $key => $country) {
          $newcountry = new Country;
          $newcountry->name_en  = $country['name'];
          $newcountry->language_id  = $country['language_id'];
          $newcountry->save();
        }
    }
}
