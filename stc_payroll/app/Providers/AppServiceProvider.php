<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Set base URL for subdirectory
        $appUrl = env('APP_URL', 'http://localhost/stc/stc_payroll/public');
        \Illuminate\Support\Facades\URL::forceRootUrl($appUrl);
        
        // Set asset URL
        if (env('ASSET_URL')) {
            \Illuminate\Support\Facades\URL::forceScheme(parse_url($appUrl, PHP_URL_SCHEME));
        }
    }
}
