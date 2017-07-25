<?php

namespace App\Http\Controllers\Api;

use App\Models\State;
use App\Models\Language;
use Dompdf\Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyTexts;
use App\Models\PropertyImages;
use App\Models\PropertyFeatures;
use App\Models\PropertyView;
use App\Models\Estateagency;
use App\Models\Features;
use App\Models\Proximities;
use App\Models\View;
use App\Models\City;
use App\Models\Country;
use App\Models\PropertyTypes;
Use App\Models\Currency;
use App\User;
use App\Models\PropertyProximities;
use Illuminate\Support\Facades\Response;

class PropertyController extends Controller
{

    private $successes = array();
    private $failures = array();
    private $general_failures = array();

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return Response
     */
    public function createUpdate(Request $request)
    {

        $input = json_decode($request->getContent(), true);

        $agency_id = $request->get('agency_id');
        $properties = $input['payload'];

        if (count($properties) < 11) {

            foreach ($properties as $p) {

                if (isset($p['external_id'])) {
                    $prop = Property::whereRaw('external_id = ? and agency_id = ?', array($p['external_id'], $agency_id))->first();

                    $updated = true;

                    if (!$prop) {
                        $prop = new Property();
                        $updated = false;
                    }

                    //handle user
                    if (isset($p['agent_email']) && preg_match('/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/', $p['agent_email'])) {
                        $user = User::whereRaw('email = ? and agency_id = ?', [$p['agent_email'], $agency_id])->first();
                        if ($user) {
                            $prop->agency_id = $agency_id;
                            $prop->agent_id = $user->id;
                        } else {
                            $this->failures[] = [
                                'status' => 'error',
                                'message' => 'Agent has not been found!',
                                'details' => [
                                    'external_id' => $p['external_id'],
                                    'agent_email' => $p['agent_email']
                                ]
                            ];
                            continue;
                        }
                    } else {
                        $this->failures[] = [
                            'status' => 'error',
                            'message' => 'Agent\'s agent_email is missing or wrong!',
                            'details' => [
                                'external_id' => $p['external_id']
                            ]
                        ];
                        continue;
                    }

                    if (isset($p['property_url'])) {
                        $prop->property_url = $p['property_url'];
                    } else {
                        $this->failures[] = [
                            'status' => 'error',
                            'message' => 'Property URL is missing!',
                            'details' => [
                                'external_id' => $p['external_id'],
                                'property_url' => null
                            ]
                        ];
                        continue;
                    }

                    $prop->rooms = (isset($p['rooms']) && is_numeric($p['rooms'])) ? $p['rooms'] : NULL;

                    $prop->external_id = $p['external_id'];

                    //handle property types
                    if (isset($p['property_type'])) {
                        $property_type = PropertyTypes::whereRaw('search_key = ?', array($p['property_type']))->first();
                        if (!$property_type) {
                            $this->failures[] = [
                                'status' => 'error',
                                'message' => 'Property type is wrong!',
                                'details' => [
                                    'external_id' => $p['external_id']
                                ]
                            ];
                            continue;
                        }
                        $prop->property_type_id = $property_type->id;
                    } else {
                        // skip property generation if property type has not been set
                        $this->failures[] = [
                            'status' => 'error',
                            'message' => 'Property type is missing!',
                            'details' => [
                                'external_id' => $p['external_id']
                            ]
                        ];
                        continue;
                    }

                    if (isset($p['country']) && $p['country'] != '' && $p['country'] != null) {
                        $country = Country::where('name_en', $p['country'])->first();
                        if (!$country) {
                            $this->failures[] = [
                                'status' => 'error',
                                'message' => 'Property Country doesn\'t look to be real!',
                                'details' => [
                                    'external_id' => $p['external_id'],
                                    'country' => $p['country']
                                ]
                            ];
                            continue;
                        } else {
                            $prop->geo_lat = $country->geo_lat;
                            $prop->geo_lng = $country->geo_lng;
                        }
                    } else {
                        $this->failures[] = [
                            'status' => 'error',
                            'message' => 'Property Country is missing or wrong!',
                            'details' => [
                                'external_id' => $p['external_id']
                            ]
                        ];
                        continue;
                    }

                    if (isset($p['state']) && $p['state'] != '' && $p['state'] != null) {
                        $state = State::where('name_en', $p['state'])->where('country_id', $country->id)->first();
                        if (!$state) {
                            $state = new State();
                            $state->country_id = $country->id;
                            $state->language_id = $country->language_id;
                            $state->name_en = $p['state'];
                            $state->save();
                        } else {
                            $prop->geo_lat = $state->geo_lat;
                            $prop->geo_lng = $state->geo_lng;
                        }
                    } else {
                        $state = new State();
                    }

                    if (isset($p['city']) && $p['city'] != '' && $p['city'] != null) {
                        $city = City::where('name_en', $p['city'])->where('country_id', $country->id)->first();
                        if (!$city) {
                            $prop->city = $p['city'];
                            $city = new City();
                        }
                    } else {
                        $city = new City();
                    }

                    if (isset($p['geo_lat']) && is_numeric($p['geo_lat'])) {
                        $prop->geo_lat = $p['geo_lat'];
                    }

                    if (isset($p['geo_lng']) && is_numeric($p['geo_lng'])) {
                        $prop->geo_lng = $p['geo_lng'];
                    }

                    if (isset($p['street_address'])) {
                        $prop->street_address = $p['street_address'];
                    }

                    $prop->country_id = $country->id;
                    $prop->state_id = $state->id;
                    $prop->city_id = $city->id;

                    if (isset($p['total_living_area']) && is_numeric($p['total_living_area'])) {
                        $prop->total_living_area = $p['total_living_area'];
                    } else {
                        $prop->total_living_area = NULL;
                    }

                    if (isset($p['total_living_area_type'])
                        && ($p['total_living_area_type'] == 'sq.m.' || $p['total_living_area_type'] == 'sq.ft.')
                    ) {
                        $prop->total_living_area_type = $p['total_living_area_type'];
                    } else {
                        $prop->total_living_area_type = NULL;
                    }

                    if (isset($p['total_garden_area']) && is_numeric($p['total_garden_area'])) {
                        $prop->total_garden_area = $p['total_garden_area'];
                    } else {
                        $prop->total_garden_area = NULL;
                    }

                    if (isset($p['total_garden_area_type'])
                        && ($p['total_garden_area_type'] == 'sq.m.' || $p['total_garden_area_type'] == 'sq.ft.')
                    ) {
                        $prop->total_garden_area_type = $p['total_garden_area_type'];
                    } else {
                        $prop->total_garden_area_type = NULL;
                    }

                    if (isset($p['price_on_request']) && $p['price_on_request']) {
                        $prop->price_on_request = 1;
                        $prop->price = 0;
                    } else {
                        if (isset($p['sell_price_original'])) {
                            $prop->price = $p['sell_price_original'];
                            $prop->price_on_request = 0;

                            if (isset($p['sell_price_original_currency'])) {
                                $cur = Currency::where('currency', $p['sell_price_original_currency'])->where('exchangeable', 1)->first();
                                if (!$cur) {
                                    $cur = Currency::get_eur();
                                }
                                $prop->price_currency_id = $cur->id;
                            } else {
                                $cur = Currency::get_eur();
                                $prop->price_currency_id = $cur->id;
                            }

                        } else {
                            $prop->price_on_request = 1;
                            $prop->price = 0;
                        }
                    }

                    if (isset($p['property_tax'])) {
                        $prop->property_tax = $p['property_tax'];
                        if (isset($p['property_tax_currency'])) {
                            $cur = Currency::where('currency', $p['property_tax_currency'])->where('exchangeable', 1)->first();
                            if (!$cur) {
                                $cur = Currency::get_eur();
                            }
                            $prop->property_tax_currency_id = $cur->id;
                        } else {
                            $cur = Currency::get_eur();
                            $prop->property_tax_currency_id = $cur->id;
                        }
                    } else {
                        $prop->property_tax = 0;
                    }

                    if (isset($p['personal_property_tax'])) {
                        $prop->personal_property_tax = $p['personal_property_tax'];
                        if (isset($p['personal_property_tax_currency'])) {
                            $cur = Currency::where('currency', $p['personal_property_tax_currency'])->where('exchangeable', 1)->first();
                            if (!$cur) {
                                $cur = Currency::get_eur();
                            }
                            $prop->personal_property_tax_currency_id = $cur->id;
                        } else {
                            $cur = Currency::get_eur();
                            $prop->personal_property_tax_currency_id = $cur->id;
                        }
                    } else {
                        $prop->personal_property_tax = 0;
                    }

                    if (isset($p['monthly_fee']) && is_numeric($p['monthly_fee'])) {
                        $prop->monthly_fee = $p['monthly_fee'];
                        if (isset($p['monthly_fee_currency'])) {
                            $cur = Currency::where('currency', $p['monthly_fee_currency'])->where('exchangeable', 1)->first();
                            if (!$cur) {
                                $cur = Currency::get_eur();
                            }
                            $prop->monthly_fee_currency_id = $cur->id;
                        } else {
                            $cur = Currency::get_eur();
                            $prop->monthly_fee_currency_id = $cur->id;
                        }
                    }

                    if (isset($p['build_year'])) {
                        if (is_numeric($p['build_year']) && is_int($p['build_year']) && strlen((string)$p['build_year']) == 4) {
                            $prop->build_year = (int)$p['build_year'];
                        }
                    } else
                        $prop->build_year = NULL;

                    if (!isset($p['texts']) || !is_array($p['texts']) || empty($p['texts'])) {
                        $this->failures[] = [
                            'status' => 'error',
                            'message' => 'Property Texts area is missing or wrong!',
                            'details' => [
                                'external_id' => $p['external_id']
                            ]
                        ];
                        continue;
                    } else {
                        $property_texts = ($prop->texts()->first()) ? $prop->texts()->first() : new PropertyTexts();
                        if (!$this->process_texts($p['texts'], $property_texts, $prop)) {
                            // failures has been add in process_texts function
                            continue;
                        }
                    }

                    $prop->api_updated_at = time();
                    $prop->save();

                    $property_texts->property_id = $prop->id;
                    $property_texts->save();

                    if (isset($p['property_images'])) {
                        $obj = array();
                        $obj[] = [
                            'external_id' => $p['external_id'],
                            'images' => $p['property_images']
                        ];
                        $this->process_images($obj, $agency_id);
                    }

                    if (isset($p['features'])) {
                        $obj = array();
                        $obj[] = [
                            'external_id' => $p['external_id'],
                            'features' => $p['features']
                        ];
                        $this->process_features($obj, $agency_id);
                    }

                    if (isset($p['proximities'])) {
                        $obj = array();
                        $obj[] = [
                            'external_id' => $p['external_id'],
                            'proximities' => $p['proximities']
                        ];
                        $this->process_proximities($obj, $agency_id);
                    }

                    if (isset($p['views'])) {
                        $obj = array();
                        $obj[] = [
                            'external_id' => $p['external_id'],
                            'views' => $p['views']
                        ];
                        $this->process_views($obj, $agency_id);
                    }

                    $this->successes[] = [
                        'status' => 'success',
                        'message' => 'Property has been saved!',
                        'details' => [
                            'external_id' => $prop->external_id,
                            'action' => ($updated) ? 'updated' : 'created',
                        ]
                    ];
                } else {
                    $this->failures[] = [
                        'status' => 'error',
                        'message' => 'Property external_id is missing!',
                        'details' => [
                            'external_id' => null
                        ]
                    ];
                    continue;
                }
            }
        } else {
            $this->general_failures[] = 'Property Api only accepts requests with number of properties less or equal to ten!';
        }

        return $this->_generate_result();
    }

    public function show(Request $request)
    {
        $input = json_decode($request->getContent(), true);

        $agency_id = $request->get('agency_id');
        $properties = $input['payload'];

        if (is_array($properties) && count($properties) < 11) {
            foreach ($properties as $p) {
                if (isset($p['external_id'])) {
                    $property = Property::whereRaw('external_id = ? and agency_id = ?', [$p['external_id'], $agency_id])->first();
                    if ($property) {
                        $this->successes[] = $property->api_format();
                    } else {
                        $this->failures[] = [
                            'status' => 'error',
                            'message' => 'Property was not found!',
                            'details' => [
                                'external_id' => $p['external_id']
                            ]
                        ];
                    }
                } else {
                    $this->failures[] = [
                        'status' => 'error',
                        'message' => 'Property external_id is missing!',
                        'details' => [
                            'external_id' => null
                        ]
                    ];
                    continue;
                }
            }
        } else {
            $this->general_failures[] = 'Property Api only accepts requests with number of properties less or equal to ten!';
        }
        return $this->_generate_result();
    }

    public function all(Request $request)
    {
        $agency_id = $request->get('agency_id');
        $input = json_decode($request->getContent(), true);

        if (isset($input['payload']) && !empty($input['payload'])) {
            if (isset($input['payload']['page']) && $input['payload']['page'] && is_integer($input['payload']['page'])) {
                $page = $input['payload']['page'];
            } else {
                $page = 1;
            }
        } else {
            $page = 1;
        }

        $skip = ($page - 1) * 100;

        $properties = Property::where('agency_id', '=', $agency_id)->skip($skip)->take(100)->get();
        if (count($properties)) {
            foreach ($properties as $prop) {
                $this->successes[] = $prop->api_format();
            }
        } else {
            $this->failures[] = [
                'status' => 'error',
                'message' => 'No properties were found!'
            ];
        }
        return $this->_generate_result();
    }

    public function price(Request $request)
    {
        $input = json_decode($request->getContent(), true);

        $agency_id = $request->get('agency_id');
        $properties = $input['payload'];

        if (is_array($properties) && count($properties) < 11) {
            foreach ($properties as $p) {
                if (isset($p['external_id'])) {
                    $prop = Property::whereRaw('external_id = ? and agency_id = ?', array($p['external_id'], $agency_id))->first();
                    if ($prop) {

                        if (isset($p['price_on_request']) && $p['price_on_request']) {
                            $prop->price_on_request = 1;
                            $prop->price = 0;
                        } else {
                            if (isset($p['sell_price_original'])) {
                                $prop->price = $p['sell_price_original'];
                                $prop->price_on_request = 0;

                                if (isset($p['sell_price_original_currency'])) {
                                    $cur = Currency::where('currency', $p['sell_price_original_currency'])->where('exchangeable', 1)->first();
                                    if (!$cur) {
                                        $cur = Currency::get_eur();
                                    }
                                    $prop->price_currency_id = $cur->id;
                                } else {
                                    $cur = Currency::get_eur();
                                    $prop->price_currency_id = $cur->id;
                                }

                            } else {
                                $prop->price_on_request = 1;
                                $prop->price = 0;
                            }
                        }

                        if (isset($p['property_tax'])) {
                            $prop->property_tax = $p['property_tax'];
                            if (isset($p['property_tax_currency'])) {
                                $cur = Currency::where('currency', $p['property_tax_currency'])->where('exchangeable', 1)->first();
                                if (!$cur) {
                                    $cur = Currency::get_eur();
                                }
                                $prop->property_tax_currency_id = $cur->id;
                            } else {
                                $cur = Currency::get_eur();
                                $prop->property_tax_currency_id = $cur->id;
                            }
                        } else {
                            $prop->property_tax = 0;
                        }

                        if (isset($p['personal_property_tax'])) {
                            $prop->personal_property_tax = $p['personal_property_tax'];
                            if (isset($p['personal_property_tax_currency'])) {
                                $cur = Currency::where('currency', $p['personal_property_tax_currency'])->where('exchangeable', 1)->first();
                                if (!$cur) {
                                    $cur = Currency::get_eur();
                                }
                                $prop->personal_property_tax_currency_id = $cur->id;
                            } else {
                                $cur = Currency::get_eur();
                                $prop->personal_property_tax_currency_id = $cur->id;
                            }
                        } else {
                            $prop->personal_property_tax = 0;
                        }

                        if (isset($p['monthly_fee']) && is_numeric($p['monthly_fee'])) {
                            $prop->monthly_fee = $p['monthly_fee'];
                            if (isset($p['monthly_fee_currency'])) {
                                $cur = Currency::where('currency', $p['monthly_fee_currency'])->where('exchangeable', 1)->first();
                                if (!$cur) {
                                    $cur = Currency::get_eur();
                                }
                                $prop->monthly_fee_currency_id = $cur->id;
                            } else {
                                $cur = Currency::get_eur();
                                $prop->monthly_fee_currency_id = $cur->id;
                            }
                        }

                        $prop->api_updated_at = time();
                        $prop->save();

                        $this->successes[] = [
                            'status' => 'success',
                            'message' => 'Property prices were updated!',
                            'details' => [
                                'external_id' => $prop->external_id,
                            ]
                        ];
                    } else {
                        $this->failures[] = [
                            'status' => 'error',
                            'message' => 'Property has not been found!',
                            'details' => [
                                'external_id' => $p['external_id']
                            ]
                        ];
                        continue;
                    }
                } else {
                    $this->failures[] = [
                        'status' => 'error',
                        'message' => 'Property external_id is missing!',
                        'details' => [
                            'external_id' => null
                        ]
                    ];
                    continue;
                }
            }
        } else {
            $this->general_failures[] = 'Property Api only accepts requests with number of properties less or equal to ten!';
        }
        return $this->_generate_result();
    }

    public function sold(Request $request)
    {
        $input = json_decode($request->getContent(), true);

        $agency_id = $request->get('agency_id');
        $properties = $input['payload'];

        if (is_array($properties) && count($properties) < 11) {
            foreach ($properties as $p) {
                if (isset($p['external_id'])) {

                    $prop = Property::whereRaw('external_id = ? and agency_id = ?', array($p['external_id'], $agency_id))->first();
                    if ($prop) {
                        if (isset($p['sold_price']) && is_numeric($p['sold_price'])) {

                            $prop->mark_as_sold = 1;
                            $prop->sold_price = $p['sold_price'];

                            if (isset($p['sold_price_currency'])) {
                                $cur = Currency::where('currency', $p['sold_price_currency'])->where('exchangeable', 1)->first();
                                if (!$cur) {
                                    $cur = Currency::get_eur();
                                }
                                $prop->sold_price_currency_id = $cur->id;
                            } else {
                                $cur = Currency::get_eur();
                                $prop->sold_price_currency_id = $cur->id;
                            }

                            $prop->api_updated_at = time();
                            $prop->save();

                            $this->successes[] = [
                                'status' => 'success',
                                'message' => 'Property has been marked as sold!',
                                'details' => [
                                    'external_id' => $prop->external_id
                                ]
                            ];
                        } else {
                            $this->failures[] = [
                                'status' => 'error',
                                'message' => 'Property sold_price is missing or wrong!',
                                'details' => [
                                    'external_id' => $p['external_id']
                                ]
                            ];
                            continue;
                        }
                    } else {
                        $this->failures[] = [
                            'status' => 'error',
                            'message' => 'Property has not been found!',
                            'details' => [
                                'external_id' => $p['external_id']
                            ]
                        ];
                        continue;
                    }
                } else {
                    $this->failures[] = [
                        'status' => 'error',
                        'message' => 'Property external_id is missing!',
                        'details' => [
                            'external_id' => null,
                        ]
                    ];
                    continue;
                }
            }
        } else {
            $this->general_failures[] = 'Property Api only accepts requests with number of properties less or equal to ten!';
        }
        return $this->_generate_result();
    }

    public function localisation(Request $request)
    {
        $input = json_decode($request->getContent(), true);

        $agency_id = $request->get('agency_id');
        $localisations = $input['payload'];
        if (is_array($localisations) && count($localisations) < 11) {
            foreach ($localisations as $p) {
                if (isset($p['external_id'])) {

                    $prop = Property::whereRaw('external_id = ? and agency_id = ?', array($p['external_id'], $agency_id))->first();
                    if ($prop) {

                        if (isset($p['texts']) && is_array($p['texts'])) {

                            $property_texts = $prop->texts()->first();

                            if ($this->process_texts($p['texts'], $property_texts, $prop)) {
                                $prop->api_updated_at = time();
                                $prop->save();

                                $property_texts->save();

                                $this->successes[] = [
                                    'status' => 'success',
                                    'message' => 'Property Texts were updated!',
                                    'details' => [
                                        'external_id' => $prop->external_id
                                    ]
                                ];
                            } else {
                                // failures has been add in process_texts function
                                continue;
                            }
                        } else {
                            $this->failures[] = [
                                'status' => 'error',
                                'message' => 'Texts section is missing!',
                                'details' => [
                                    'external_id' => $prop->external_id,
                                    'texts' => null
                                ]
                            ];
                            continue;
                        }
                    } else {
                        $this->failures[] = [
                            'status' => 'error',
                            'message' => 'Property has not been found!',
                            'details' => [
                                'external_id' => $p['external_id']
                            ]
                        ];
                        continue;
                    }
                } else {
                    $this->failures[] = [
                        'status' => 'error',
                        'message' => 'Property external_id is missing!',
                        'details' => [
                            'external_id' => null
                        ]
                    ];
                    continue;
                }
            }
        } else {
            $this->general_failures[] = 'Property Api only accepts requests with number of properties less or equal to ten!';
        }
        return $this->_generate_result();
    }

    public function images(Request $request)
    {
        $input = json_decode($request->getContent(), true);
        $agency_id = $request->get('agency_id');
        $properties = $input['payload'];
        if (is_array($properties) && count($properties) < 11) {
            $this->process_images($properties, $agency_id);
        } else {
            $this->general_failures[] = 'Property Api only accepts requests with number of properties less or equal to ten!';
        }
        return $this->_generate_result();
    }

    public function features(Request $request)
    {
        $input = json_decode($request->getContent(), true);
        $agency_id = $request->get('agency_id');
        $properties = $input['payload'];
        if (is_array($properties) && count($properties) < 11) {
            $this->process_features($properties, $agency_id);
        } else {
            $this->general_failures[] = 'Property Api only accepts requests with number of properties less or equal to ten!';
        }
        return $this->_generate_result();
    }

    public function views(Request $request)
    {
        $input = json_decode($request->getContent(), true);
        $agency_id = $request->get('agency_id');
        $properties = $input['payload'];
        if (is_array($properties) && count($properties) < 11) {
            $this->process_views($properties, $agency_id);
        } else {
            $this->general_failures[] = 'Property Api only accepts requests with number of properties less or equal to ten!';
        }
        return $this->_generate_result();
    }

    public function proximities(Request $request)
    {
        $input = json_decode($request->getContent(), true);
        $agency_id = $request->get('agency_id');
        $properties = $input['payload'];
        if (is_array($properties) && count($properties) < 11) {
            $this->process_proximities($properties, $agency_id);
        } else {
            $this->general_failures[] = 'Property Api only accepts requests with number of properties less or equal to ten!';
        }
        return $this->_generate_result();
    }

    public function destroy(Request $request)
    {
        $input = json_decode($request->getContent(), true);

        $agency_id = $request->get('agency_id');
        $properties = $input['payload'];

        if (is_array($properties) && count($properties) < 11) {
            foreach ($properties as $p) {
                if (isset($p['external_id'])) {
                    $property = Property::whereRaw('external_id = ? and agency_id = ?', [$p['external_id'], $agency_id])->first();
                    if ($property) {
                        Property::destroy($property->id);
                        $this->successes[] = [
                            'status' => 'success',
                            'message' => 'Property ' . $p['external_id'] . ' has been deleted!',
                            'details' => [
                                'external_id' => $p['external_id'],
                                'action' => 'delete'
                            ]
                        ];
                    } else {
                        $this->failures[] = [
                            'status' => 'error',
                            'message' => 'Property was not found!',
                            'details' => [
                                'external_id' => $p['external_id']
                            ]
                        ];
                    }
                } else {
                    $this->failures[] = [
                        'status' => 'error',
                        'message' => 'Property external_id is missing!',
                        'details' => [
                            'external_id' => null
                        ]
                    ];
                    continue;
                }
            }
        } else {
            $this->general_failures[] = 'Property Api only accepts requests with number of properties less or equal to ten!';
        }
        return $this->_generate_result();
    }

    private function process_texts($texts, &$prop_texts, $prop)
    {
        $languages = Language::getTranslatable();

        $updated = false;
        $failures = false;
        foreach ($languages as $lang) {
            $subject_col = 'subject_' . $lang->country_code;
            $description_col = 'description_' . $lang->country_code;
            if (
                (isset($texts[$subject_col]) && !isset($texts[$description_col])) ||
                (!isset($texts[$subject_col]) && isset($texts[$description_col]))
            ) {
                $failures = true;
                $this->failures[] = [
                    'status' => 'error',
                    'message' => 'Set of ' . $subject_col . ' and ' . $description_col . ' should be set!',
                    'details' => [
                        'external_id' => $prop->external_id,
                        'language' => $lang->country_code
                    ]
                ];
                continue;
            }

            $updated = true;

            $prop_texts->$subject_col = ($texts[$subject_col] != null && $texts[$subject_col] != '') ? $texts[$subject_col] : $prop_texts->$subject_col;
            $prop_texts->$description_col = ($texts[$description_col] != null && $texts[$description_col] != '') ? $texts[$description_col] : $prop_texts->$description_col;

        }

        if (!$updated || $failures) {
            if (!$failures) {
                $this->failures[] = [
                    'status' => 'error',
                    'message' => 'Property texts section cannot be empty!',
                    'details' => [
                        'external_id' => $prop->external_id
                    ]
                ];
            }
            return false;
        } else {
            return true;
        }
    }
	
    private function process_images($properties, $agency_id)
    {
        foreach ($properties as $property) {
            if (isset($property['external_id'])) {
                $prop = Property::whereRaw('agency_id = ? and external_id = ?', [$agency_id, $property['external_id']])->first();
                if ($prop) {
                    $prop->api_updated_at = time();
                    $prop->save();

                    if (isset($property['images']) && is_array($property['images'])) {
                        $main_image_found = false;
                        foreach ($property['images'] as $image) {
                            $image_check = self::file_get_contents_cached($image['url'],1087000);
                            if ($image_check) {
                                $pi = PropertyImages::whereRaw('url_checksum = ? and property_id = ?', [md5($image['url']), $prop->id])->first();
                                if (!$pi) {
                                    $pi = new PropertyImages();
                                    $pi->property_id = $prop->id;
                                    $pi->image_url = $image['url'];
                                    $pi->url_checksum = md5($image['url']);
                                    if (isset($image_['is_main'])) {
                                        $pi->main_image = $image['is_main'];
                                    }
                                }

                                if ($pi->s3_path == null) {
                                    $filename_parts = explode('.', $pi->image_url);
                                    $agency = Estateagency::where('id', $agency_id)->first();
                                    $s3_path = '';
                                    $s3_path .= str_replace('\'', '-', str_replace(' ', '-', strtolower($agency->public_name))) . '/';
                                    $s3_path .= $prop->id . '/';
                                    $s3_path .= md5(time() . microtime()) . '.' . end($filename_parts);
                                    $pi->s3_path = $s3_path;
                                }

                                if (!$this->s3Exists($pi->s3_path)) {
                                    $pi->s3_url = $this->uploadFileToS3Bytes($image_check, $pi->s3_path);
                                }

                                $pi->api_lock = 1;
                                $pi->save();
                                //id main image, update property
                                if ($pi->main_image == 1) {
                                    $prop->main_image_url = ($pi->s3_url) ? $pi->s3_url : $pi->image_url;
                                    $prop->save();
                                    $main_image_found = true;
                                }
                            } else {
                                $this->failures[] = [
                                    'status' => 'error',
                                    'message' => 'Provided Url is not an image!',
                                    'details' => [
                                        'external_id' => $prop->external_id,
                                        'image_url' => $image['url']
                                    ]
                                ];
                                continue;
                            }
                        }

                        if (!$main_image_found) {
                            // if non of images was marked as main, then mark first image as main
                            $img = PropertyImages::whereRaw('property_id = ? and api_lock = 1', [$prop->id])->first();
                            if ($img) {
                                $img->main_image = 1;
                                $img->save();

                                $prop->main_image_url = ($img->s3_url) ? $img->s3_url : $img->image_url;
                                $prop->save();
                            }
                        }

                        $pis = PropertyImages::whereRaw('property_id = ?', [$prop->id])->get();

                        foreach ($pis as $pi) {
                            if (isset($property['replace_all']) && $property['replace_all']) {
                                if ($pi->api_lock = 0) {
                                    $this->s3Delete($pi->s3_url);
                                    PropertyImages::destroy($pi->id);
                                    continue;
                                }
                            }

                            if ($pi->api_lock == 1) {
                                $pi->api_lock = 0;
                            }
                            $pi->save();
                        }

                        $this->successes[] = [
                            'status' => 'success',
                            'message' => 'Property Images are saved!',
                            'details' => [
                                'external_id' => $prop->external_id
                            ]
                        ];
                    } else {
                        $this->failures[] = [
                            'status' => 'error',
                            'message' => 'Property Images section is missing!',
                            'details' => [
                                'external_id' => $prop->external_id
                            ]
                        ];
                        continue;
                    }
                } else {
                    $this->failures[] = [
                        'status' => 'error',
                        'message' => 'Property can not be found!',
                        'details' => [
                            'external_id' => $property['external_id']
                        ]
                    ];
                    continue;
                }
            } else {
                $this->failures[] = [
                    'status' => 'error',
                    'message' => 'Property external_id is missing!',
                    'details' => [
                        'external_id' => null
                    ]
                ];
                continue;
            }
        }
    }

    private function process_features($properties, $agency_id)
    {
        foreach ($properties as $property) {
            if (isset($property['external_id'])) {
                $prop = Property::whereRaw('agency_id = ? and external_id = ?', [$agency_id, $property['external_id']])->first();
                if ($prop) {
                    $prop->api_updated_at = time();
                    $prop->save();

                    if (isset($property['features']) && is_array($property['features'])) {
                        PropertyFeatures::where('property_id', '=', $prop->id)->delete();
                        $feature_arr = [];
                        foreach ($property['features'] as $search_key => $number) {
                            if ($number == '' || $number == null || !$number) {
                                continue;
                            }
                            $feature_arr[] = $search_key;
                        }
                        $feature_arr = array_unique($feature_arr);
                        foreach ($feature_arr as $search_key) {
                            $feature = Features::whereRaw('search_key = ?', [$search_key])->first();
                            if ($feature) {
                                $pf = new PropertyFeatures();
                                $pf->property_id = $prop->id;
                                $pf->feature_id = $feature->id;
                                $pf->save();
                            } else {
                                $this->failures[] = [
                                    'status' => 'error',
                                    'message' => 'Property Feature ' . $search_key . ' not found!',
                                    'details' => [
                                        'external_id' => $prop->external_id,
                                        'feature' => $search_key,
                                    ]
                                ];
                                continue;
                            }
                        }
                        $this->successes[] = [
                            'status' => 'success',
                            'message' => 'Property Features are saved!',
                            'details' => [
                                'external_id' => $prop->external_id
                            ]
                        ];
                    } else {
                        $this->failures[] = [
                            'status' => 'error',
                            'message' => 'Property Features section is missing!',
                            'details' => [
                                'external_id' => $prop->external_id
                            ]
                        ];
                        continue;
                    }
                } else {
                    $this->failures[] = [
                        'status' => 'error',
                        'message' => 'Property can not be found!',
                        'details' => [
                            'external_id' => $property['external_id']
                        ]
                    ];
                    continue;
                }
            } else {
                $this->failures[] = [
                    'status' => 'error',
                    'message' => 'Property external_id is missing!',
                    'details' => [
                        'external_id' => null
                    ]
                ];
                continue;
            }
        }
    }

    private function process_proximities($properties, $agency_id)
    {
        foreach ($properties as $property) {
            if (isset($property['external_id'])) {
                $prop = Property::whereRaw('agency_id = ? and external_id = ?', [$agency_id, $property['external_id']])->first();
                if ($prop) {
                    $prop->api_updated_at = time();
                    $prop->save();

                    if (isset($property['proximities']) && is_array($property['proximities'])) {
                        PropertyProximities::where('property_id', '=', $prop->id)->delete();
                        $proximity_arr = [];
                        foreach ($property['proximities'] as $search_key => $v) {
                            if ($v == '' || $v == null || !$v) {
                                continue;
                            }
                            $proximity_arr[] = $search_key;
                        }
                        $proximity_arr = array_unique($proximity_arr);
                        foreach ($proximity_arr as $search_key) {
                            $proximity = Proximities::whereRaw('search_key = ?', [$search_key])->first();
                            if ($proximity) {
                                $pp = new PropertyProximities();
                                $pp->property_id = $prop->id;
                                $pp->proximity_id = $proximity->id;
                                $pp->save();
                            } else {
                                $this->failures[] = [
                                    'status' => 'error',
                                    'message' => 'Property Proximity ' . $search_key . ' not found!',
                                    'details' => [
                                        'external_id' => $prop->external_id,
                                        'proximity' => $search_key
                                    ]
                                ];
                                continue;
                            }
                        }
                        $this->successes[] = [
                            'status' => 'success',
                            'message' => 'Property proximities are saved!',
                            'details' => [
                                'external_id' => $prop->external_id
                            ]
                        ];
                    } else {
                        $this->failures[] = [
                            'status' => 'error',
                            'message' => 'Property Proximities section is missing!',
                            'details' => [
                                'external_id' => $prop->external_id
                            ]
                        ];
                        continue;
                    }
                } else {
                    $this->failures[] = [
                        'status' => 'error',
                        'message' => 'Property can not be found!',
                        'details' => [
                            'external_id' => $property['external_id']
                        ]
                    ];
                    continue;
                }
            } else {
                $this->failures[] = [
                    'status' => 'error',
                    'message' => 'Property external_id is missing!',
                    'details' => [
                        'external_id' => null
                    ]
                ];
                continue;
            }
        }
    }

    private function process_views($properties, $agency_id)
    {
        foreach ($properties as $property) {
            if (isset($property['external_id'])) {
                $prop = Property::whereRaw('agency_id = ? and external_id = ?', [$agency_id, $property['external_id']])->first();
                if ($prop) {
                    $prop->api_updated_at = time();
                    $prop->save();

                    if (isset($property['views']) && is_array($property['views'])) {
                        PropertyView::where('property_id', '=', $prop->id)->delete();
                        $views_arr = [];
                        foreach ($property['views'] as $search_key => $v) {
                            if ($v == '' || $v == null || !$v) {
                                continue;
                            }
                            $views_arr[] = $search_key;
                        }
                        $views_arr = array_unique($views_arr);
                        foreach ($views_arr as $search_key) {
                            $view = View::whereRaw('search_key = ?', [$search_key])->first();
                            if ($view) {
                                $pv = new PropertyView();
                                $pv->property_id = $prop->id;
                                $pv->view_id = $view->id;
                                $pv->save();
                            } else {
                                $this->failures[] = [
                                    'status' => 'error',
                                    'message' => 'Property View ' . $search_key . ' not found!',
                                    'details' => [
                                        'external_id' => $prop->external_id,
                                        'proximity' => $search_key
                                    ]
                                ];
                                continue;
                            }
                        }
                        $this->successes[] = [
                            'status' => 'success',
                            'message' => 'Property Views are saved!',
                            'details' => [
                                'external_id' => $prop->external_id
                            ]
                        ];
                    } else {
                        $this->failures[] = [
                            'status' => 'error',
                            'message' => 'Property Views section is missing!',
                            'details' => [
                                'external_id' => $prop->external_id
                            ]
                        ];
                        continue;
                    }
                } else {
                    $this->failures[] = [
                        'status' => 'error',
                        'message' => 'Property can not be found!',
                        'details' => [
                            'external_id' => $property['external_id']
                        ]
                    ];
                    continue;
                }
            } else {
                $this->failures[] = [
                    'status' => 'error',
                    'message' => 'Property external_id is missing!',
                    'details' => [
                        'external_id' => null
                    ]
                ];
                continue;
            }
        }
    }

    private
    function _generate_result()
    {
        if (empty($this->general_failures)) {
            $message = 'Data has been processed!';
            $details = [
                'successes' => $this->successes,
                'failures' => $this->failures
            ];
            if (!empty($this->successes) && !empty($this->failures)) {
                $result = 'Mixed';
            } elseif (!empty($this->successes)) {
                $result = 'Success';
            } else {
                $result = 'Error';
            }
        } else {
            $message = "Request cant be completed!";
            $result = 'General Failure';
            $details = $this->general_failures;
        }

        return Response::json([
            'result' => $result,
            'message' => $message,
            'details' => $details
        ]);
    }
}
