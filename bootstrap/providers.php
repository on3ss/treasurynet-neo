<?php

use App\Providers\AppServiceProvider;
use App\Providers\BillNoSequenceServiceProvider;
use App\Providers\Filament\DashboardPanelProvider;

return [
    AppServiceProvider::class,
    BillNoSequenceServiceProvider::class,
    DashboardPanelProvider::class,
];
