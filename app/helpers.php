<?php

if (! function_exists('setting')) {
    /**
     * Get a setting value.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        return app(\App\Services\SettingsService::class)->get($key, $default);
    }
}

if (! function_exists('log_activity')) {
    /**
     * Log an activity.
     *
     * @param string $action
     * @param string|null $description
     * @param \Illuminate\Database\Eloquent\Model|null $subject
     * @return void
     */
    function log_activity($action, $description = null, $subject = null)
    {
        app(\App\Services\ActivityLogService::class)->log($action, $description, $subject);
    }
}
