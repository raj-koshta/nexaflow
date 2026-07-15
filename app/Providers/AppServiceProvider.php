<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Facades\Event;
use App\Events\DashboardLoaded;
use App\Events\ReportGenerated;
use App\Events\NotificationCreated;
use App\Events\NotificationRead;
use App\Events\SettingsUpdated;
use App\Events\BackupCreated;
use App\Events\SearchIndexed;
use App\Events\RealtimeMessageSent;

use App\Listeners\UpdateDashboardCache;
use App\Listeners\SendNotification;
use App\Listeners\GenerateActivityLog;
use App\Listeners\SyncSearchIndex;
use App\Listeners\QueueEmails;
use App\Listeners\RefreshStatistics;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (file_exists(app_path('helpers.php'))) {
            require_once app_path('helpers.php');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Administrator') ? true : null;
        });

        Event::listen(DashboardLoaded::class, UpdateDashboardCache::class);
        Event::listen(ReportGenerated::class, RefreshStatistics::class);
        Event::listen(NotificationCreated::class, SendNotification::class);
        Event::listen(NotificationCreated::class, QueueEmails::class);
        Event::listen(SearchIndexed::class, SyncSearchIndex::class);
    }
}
