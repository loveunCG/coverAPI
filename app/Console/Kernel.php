<?php
/**
 * this is Cron
 */
namespace App\Console;

use Illuminate\Support\Facades\Storage;

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
        'App\Console\Commands\NotificationCronJobCommand',
        //
    ];

    public $count = 0;

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $this->count++;
        $schedule->command('send:notification')->everyMinute();
        /*$schedule->call(function () {
            Storage::disk('local')->put('file.txt', $this->count);
        })->everyMinute();*/
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
