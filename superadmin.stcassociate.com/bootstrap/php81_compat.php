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

// PHP 8.1+ adds $_FILES[*]['full_path']. Old Symfony FileBag only accepts
// error/name/size/tmp_name/type, so Laravel then treats the filename string
// as an uploaded file and throws createFromBase() TypeError.
if (!empty($_FILES) && is_array($_FILES)) {
    $stripUploadFullPath = static function (&$node) use (&$stripUploadFullPath) {
        if (!is_array($node)) {
            return;
        }
        unset($node['full_path']);
        foreach ($node as &$child) {
            if (is_array($child)) {
                $stripUploadFullPath($child);
            }
        }
        unset($child);
    };
    $stripUploadFullPath($_FILES);
}
