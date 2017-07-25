<?php

use Illuminate\Database\Seeder;
use App\Models\Country;
class CountriesZoomLevel extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = 
        [
          'AD' => 3,
          'AE' => 3,
          'AF' => 3,
          'AG' => 3,
          'AI' => 3,
          'AL' => 3,
          'AM' => 3,
          'AO' => 3,
          'AQ' => 3,
          'AR' => 2,
          'AS' => 3,
          'AT' => 3,
          'AU' => 2,
          'AW' => 3,
          'AX' => 3,
          'AZ' => 3,
          'BA' => 3,
          'BB' => 5,
          'BD' => 3,
          'BE' => 4,
          'BF' => 3,
          'BG' => 3,
          'BH' => 3,
          'BI' => 3,
          'BJ' => 3,
          'BL' => 3,
          'BM' => 3,
          'BN' => 3,
          'BO' => 3,
          'BQ' => 3,
          'BR' => 2,
          'BS' => 3,
          'BT' => 3,
          'BV' => 3,
          'BW' => 3,
          'BY' => 3,
          'BZ' => 3,
          'CA' => 2,
          'CC' => 3,
          'CD' => 2,
          'CF' => 3,
          'CG' => 3,
          'CH' => 3,
          'CI' => 3,
          'CK' => 3,
          'CL' => 3,
          'CM' => 3,
          'CN' => 2,
          'CO' => 2,
          'CR' => 3,
          'CU' => 3,
          'CV' => 3,
          'CW' => 3,
          'CX' => 3,
          'CY' => 3,
          'CZ' => 3,
          'DE' => 3,
          'DJ' => 3,
          'DK' => 3,
          'DM' => 3,
          'DO' => 3,
          'DZ' => 2,
          'EC' => 3,
          'EE' => 3,
          'EG' => 3,
          'EH' => 3,
          'ER' => 3,
          'ES' => 3,
          'ET' => 3,
          'FI' => 2,
          'FJ' => 3,
          'FK' => 3,
          'FM' => 3,
          'FO' => 3,
          'FR' => 2,
          'GA' => 3,
          'GB' => 3,
          'GD' => 5,
          'GE' => 3,
          'GF' => 3,
          'GG' => 3,
          'GH' => 3,
          'GI' => 3,
          'GL' => 2,
          'GM' => 3,
          'GN' => 3,
          'GP' => 5,
          'GQ' => 3,
          'GR' => 3,
          'GS' => 3,
          'GT' => 3,
          'GU' => 3,
          'GW' => 3,
          'GY' => 3,
          'HK' => 3,
          'HM' => 3,
          'HN' => 3,
          'HR' => 4,
          'HT' => 3,
          'HU' => 3,
          'ID' => 2,
          'IE' => 3,
          'IL' => 3,
          'IM' => 3,
          'IN' => 2,
          'IO' => 3,
          'IQ' => 3,
          'IR' => 3,
          'IS' => 2,
          'IT' => 3,
          'JE' => 3,
          'JM' => 3,
          'JO' => 3,
          'JP' => 2,
          'KE' => 3,
          'KG' => 3,
          'KH' => 3,
          'KI' => 3,
          'KM' => 3,
          'KN' => 3,
          'KP' => 3,
          'KR' => 3,
          'KW' => 3,
          'KY' => 3,
          'KZ' => 2,
          'LA' => 3,
          'LB' => 3,
          'LC' => 5,
          'LI' => 3,
          'LK' => 3,
          'LR' => 3,
          'LS' => 3,
          'LT' => 3,
          'LU' => 3,
          'LV' => 3,
          'LY' => 3,
          'MA' => 3,
          'MC' => 4,
          'MD' => 3,
          'ME' => 3,
          'MF' => 3,
          'MG' => 2,
          'MH' => 3,
          'MK' => 3,
          'ML' => 3,
          'MM' => 3,
          'MN' => 2,
          'MO' => 3,
          'MP' => 3,
          'MQ' => 5,
          'MR' => 3,
          'MS' => 3,
          'MT' => 3,
          'MU' => 3,
          'MV' => 3,
          'MW' => 3,
          'MX' => 2,
          'MY' => 3,
          'MZ' => 3,
          'NA' => 3,
          'NC' => 3,
          'NE' => 3,
          'NF' => 3,
          'NG' => 2,
          'NI' => 3,
          'NL' => 3,
          'NO' => 3,
          'NP' => 3,
          'NR' => 3,
          'NU' => 3,
          'NZ' => 2,
          'OM' => 3,
          'PA' => 3,
          'PE' => 2,
          'PF' => 3,
          'PG' => 2,
          'PH' => 3,
          'PK' => 3,
          'PL' => 3,
          'PM' => 3,
          'PN' => 3,
          'PR' => 3,
          'PS' => 3,
          'PT' => 3,
          'PW' => 3,
          'PY' => 3,
          'QA' => 3,
          'RE' => 3,
          'RO' => 3,
          'RS' => 3,
          'RU' => 2,
          'RW' => 3,
          'SA' => 2,
          'SB' => 3,
          'SC' => 3,
          'SD' => 2,
          'SE' => 2,
          'SG' => 3,
          'SH' => 3,
          'SI' => 4,
          'SJ' => 3,
          'SK' => 3,
          'SL' => 3,
          'SM' => 3,
          'SN' => 3,
          'SO' => 3,
          'SR' => 3,
          'SS' => 3,
          'ST' => 3,
          'SV' => 3,
          'SX' => 3,
          'SY' => 3,
          'SZ' => 3,
          'TC' => 3,
          'TD' => 3,
          'TF' => 3,
          'TG' => 3,
          'TH' => 2,
          'TJ' => 3,
          'TK' => 3,
          'TL' => 3,
          'TM' => 3,
          'TN' => 3,
          'TO' => 3,
          'TR' => 2,
          'TT' => 3,
          'TV' => 3,
          'TW' => 3,
          'TZ' => 3,
          'UA' => 2,
          'UG' => 3,
          'UM' => 3,
          'US' => 2,
          'UY' => 3,
          'UZ' => 3,
          'VA' => 3,
          'VC' => 3,
          'VE' => 3,
          'VG' => 3,
          'VI' => 3,
          'VN' => 3,
          'VU' => 3,
          'WF' => 3,
          'WS' => 3,
          'YE' => 3,
          'YT' => 3,
          'ZA' => 2,
          'ZM' => 3,
          'ZW' => 3,
          'SH' => 3,
          'CZ' => 3,
          'CI' => 3,
          'MM' => 3,
          'MK' => 3,
        ];
        
      foreach ($countries as $key => $value) {
        Country::where('iso',$key)->update(['map_zoom_level'=>$value]);
      }
    }
}
