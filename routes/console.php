<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\CleanupNotifications;
use App\Jobs\CleanupFiles;
use App\Jobs\GenerateReport;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Phase 154: Scheduler Configuration
Schedule::command('queue:flush')->daily(); // Queue Cleanup
Schedule::job(new CleanupNotifications)->daily(); // Old Notifications Cleanup
Schedule::job(new CleanupFiles)->daily(); // Temporary File Cleanup
Schedule::job(new GenerateReport)->daily(); // Generate Reports
