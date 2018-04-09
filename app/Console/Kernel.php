<?php
/**
 * this is Cron
 */
namespace App\Console;

use Illuminate\Support\Facades\Storage;
use App\Model\JobsModel;
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
        $schedule->call(function () {
        })->everyMinute();

        // $schedule->command('inspire')
        //          ->hourly();
    }

    public function expiredJob()
    {
        $jobs = JobsModel::all();
        foreach ($jobs as $job) {
        }
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
