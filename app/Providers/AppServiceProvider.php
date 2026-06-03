<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        try {
            if (Schema::hasTable('settings')) {
                $settings = Setting::pluck('value', 'key');

                // Set Nama Aplikasi secara dinamis
                if (isset($settings['app_name'])) {
                    config(['app.name' => $settings['app_name']]);
                }

                // Bagikan variabel ke seluruh View
                View::share('settings', $settings);
                View::share('appName', $settings['app_name'] ?? config('app.name'));
            }
        } catch (\Exception $e) {
        }
    }
}