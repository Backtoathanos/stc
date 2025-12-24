<?php

if (!function_exists('asset_url')) {
    /**
     * Generate asset URL with subdirectory support
     *
     * @param  string  $path
     * @return string
     */
    function asset_url($path)
    {
        $basePath = config('app.base_path', '');
        if (empty($basePath) || $basePath === '/') {
            return asset($path);
        }
        return $basePath . '/public/' . ltrim($path, '/');
    }
}

if (!function_exists('base_url')) {
    /**
     * Generate base URL with subdirectory support
     *
     * @param  string  $path
     * @return string
     */
    function base_url($path = '')
    {
        $basePath = config('app.base_path', '');
        if (empty($basePath) || $basePath === '/') {
            return url($path);
        }
        if (empty($path)) {
            return $basePath;
        }
        return $basePath . '/' . ltrim($path, '/');
    }
}

