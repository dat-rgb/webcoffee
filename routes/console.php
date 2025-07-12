<?php

use App\Jobs\CheckExpiredOrders;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\CheckExpiredOrdersCommand;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

return function (Schedule $schedule) {
    Schedule::job(new CheckExpiredOrders)
    ->everyMinute()
    ->withoutOverlapping();
};

