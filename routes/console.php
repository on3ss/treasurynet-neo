<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Run daily to check and generate financial years if needed
// Schedule::command('financial-years:generate')->daily();

// Or run at the start of each financial year (April 1st)
// Schedule::command('financial-years:generate')->yearlyOn(4, 1, '00:00');

// Or run monthly to ensure everything is up to date
Schedule::command('financial-years:generate')->monthlyOn(1, '00:00');
