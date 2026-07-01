<?php

/**
 * PHP 8.1+ compatibility for Laravel 6 on legacy vendor packages.
 * Loaded before Composer autoload so deprecations during class loading are ignored.
 */
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED & ~E_NOTICE);

set_error_handler(static function ($severity, $message, $file, $line) {
    if (in_array($severity, [E_DEPRECATED, E_USER_DEPRECATED, E_NOTICE], true)) {
        return true;
    }

    return false;
});
