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
        $basePath = '/stc/stc_payroll/public';
        return $basePath . '/' . ltrim($path, '/');
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
        $basePath = '/stc/stc_payroll/public';
        if (empty($path)) {
            return $basePath;
        }
        return $basePath . '/' . ltrim($path, '/');
    }
}

