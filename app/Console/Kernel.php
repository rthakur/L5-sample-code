<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Languages_library_import::class,
        Commands\Geolocation_languages_export::class,
        Commands\Geolocation_languages_import::class,
        Commands\Agent_notify_check_property::class,
        Commands\Agent_notify_check_property_sold::class,
        Commands\Agent_notify_check_translate::class,
        Commands\Get_Exchange_Rates::class,
        Commands\Get_Countries_Geolocation::class,
        Commands\Get_States_Geolocation::class,
        Commands\Get_Cities_Geolocation::class,
        Commands\Geolocation_confirm::class,
        Commands\Countries_add_iso_code::class,
        Commands\Property_translation::class,
        Commands\Property_prices::class,
        Commands\Property_locations::class,
        Commands\Locations_Create_Searchkeys::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('agent:notify')->everyMinute();
        $schedule->command('agent:notify_check_sold')->everyFiveMinutes();
        //  $schedule->command('agent:notify_check_translate')->everyThirtyMinutes(); todo: uncomment after email creation :)
        $schedule->command('exchange_rate')->daily();
        $schedule->command('geolocation:confirm')->everyThirtyMinutes()->after(function () {
            $this->call('localisation:geolocation_export');
        });
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
