<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\State;
use App\Models\City;

class JsonResponseController extends Controller
{
    public function getStates(Request $request)
    {
        return State::where('country_id', $request->country_id)->orderBy('name_en')->get();
    }

    public function getCities(Request $request)
    {
        return City::where('country_id', $request->country_id)->where('name_en', 'like', $request->str . '%')->orderBy('name_en')->get()->pluck('name_en');
    }
}
