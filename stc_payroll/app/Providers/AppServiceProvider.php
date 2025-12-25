<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Get APP_URL from environment or detect automatically
        $appUrl = env('APP_URL');
        
        // If APP_URL is not set, detect it automatically
        if (empty($appUrl) && app()->runningInConsole() === false) {
            try {
                $scheme = request()->getScheme();
                $host = request()->getHttpHost();
                $path = dirname(request()->getScriptName());
                
                // Remove /public if present
                $path = str_replace('/public', '', $path);
                
                $appUrl = $scheme . '://' . $host . $path . '/public';
            } catch (\Exception $e) {
                // Fallback if request is not available
                $appUrl = 'http://localhost/stc/stc_payroll/public';
            }
        }
        
        // Fallback if still empty
        if (empty($appUrl)) {
            $appUrl = 'http://localhost/stc/stc_payroll/public';
        }
        
        // Ensure APP_URL ends with /public (PHP 7.2 compatible)
        $appUrlLength = strlen($appUrl);
        $publicSuffix = '/public';
        if ($appUrlLength < strlen($publicSuffix) || substr($appUrl, -strlen($publicSuffix)) !== $publicSuffix) {
            $appUrl = rtrim($appUrl, '/') . '/public';
        }
        
        // Calculate base path for views (without /public)
        $parsedPath = parse_url($appUrl, PHP_URL_PATH);
        $basePath = $parsedPath ? rtrim($parsedPath, '/public') : '/';
        if (empty($basePath) || $basePath === '/') {
            $basePath = '/';
        }
        
        // Create user-facing URL (without /public) for URL generation
        $scheme = parse_url($appUrl, PHP_URL_SCHEME) ?: 'http';
        $host = parse_url($appUrl, PHP_URL_HOST) ?: 'localhost';
        $userFacingUrl = rtrim($scheme . '://' . $host . $basePath, '/');
        
        // Set base URL for Laravel's url() helper (without /public)
        // This ensures url() and route() generate clean URLs without /public/
        URL::forceRootUrl($userFacingUrl);
        
        // Set scheme (http/https)
        $scheme = parse_url($appUrl, PHP_URL_SCHEME);
        if ($scheme) {
            URL::forceScheme($scheme);
        }
        
        // Share base URL with all views
        view()->share('baseUrl', $basePath);
        
        // Store base path in config for helper functions
        config(['app.base_path' => $basePath]);
    }
}
