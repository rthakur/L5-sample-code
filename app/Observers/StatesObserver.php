<?php
namespace App\Observers;

use App\Models\State;
use App\Helpers\LocationsSKHelper;

class StatesObserver
{

    public function saved(State $state)
    {
        if ($state->isDirty() && !$state::$DISABLED_OBSERVER) {
            $updating = $state->getDirty();
            if (isset($updating['name_en'])) {
                $state::$DISABLED_OBSERVER = true;
                $state = LocationsSKHelper::generate($state, 'states');
                $state->save();
                $state::$DISABLED_OBSERVER = false;
            }
        }
    }

    public function deleted(State $state)
    {
        $cities = $state->cities()->get();
        if (count($cities)) {
            foreach ($cities as $city) {
                $city->delete();
            }
        }
    }
}