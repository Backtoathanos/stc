<?php

/**
 * Resolve `stc_product_image` column for HTML/JSON: full https URL (e.g. R2) unchanged;
 * legacy filenames get STC_PRODUCT_IMAGE_LOCAL_BASE_URL prefix.
 *
 * Override base: set env STC_PRODUCT_IMAGE_URL (same as Laravel STC_PRODUCT_IMAGE_URL).
 */
if (!defined('STC_PRODUCT_IMAGE_LOCAL_BASE_URL')) {
    $MCU_product_image_base = getenv('STC_PRODUCT_IMAGE_URL');
    define(
        'STC_PRODUCT_IMAGE_LOCAL_BASE_URL',
        ($MCU_product_image_base !== false && $MCU_product_image_base !== '')
            ? rtrim($MCU_product_image_base, '/')
            : 'https://stcassociate.com/stc_symbiote/stc_product_image'
    );
}

if (!function_exists('stc_product_image_url')) {
    function stc_product_image_url($stored)
    {
        $stored = trim((string) $stored);
        if ($stored === '') {
            return '';
        }

        return preg_match('#^https?://#i', $stored)
            ? $stored
            : STC_PRODUCT_IMAGE_LOCAL_BASE_URL . '/' . str_replace('\\', '/', $stored);
    }
}

if (!defined('STC_NEARMISS_IMAGE_LOCAL_BASE_URL')) {
    $MCU_nearmiss_base = getenv('STC_NEARMISS_IMAGE_URL');
    if ($MCU_nearmiss_base === false || $MCU_nearmiss_base === '') {
        $MCU_nearmiss_base = getenv('STC_TBM_IMAGE_URL');
    }
    define(
        'STC_NEARMISS_IMAGE_LOCAL_BASE_URL',
        ($MCU_nearmiss_base !== false && $MCU_nearmiss_base !== '')
            ? rtrim($MCU_nearmiss_base, '/')
            : 'https://stcassociate.com/stc_sub_agent47/safety_img'
    );
}

if (!function_exists('stc_nearmiss_image_url')) {
    /**
     * `stc_safetynearmiss_img_location`: full https URL (R2) unchanged; legacy filenames use safety_img base.
     */
    function stc_nearmiss_image_url($stored)
    {
        return stc_safety_image_url($stored);
    }
}

if (!function_exists('stc_tbm_image_url')) {
    /**
     * `stc_safetytbm_img_location`: full https URL (R2) unchanged; legacy filenames use safety_img base.
     */
    function stc_tbm_image_url($stored)
    {
        return stc_safety_image_url($stored);
    }
}

if (!function_exists('stc_safety_image_url')) {
    function stc_safety_image_url($stored)
    {
        $stored = trim((string) $stored);
        if ($stored === '') {
            return '';
        }

        if (preg_match('#^https?://#i', $stored)) {
            return $stored;
        }

        $base = basename(str_replace('\\', '/', $stored));

        return STC_NEARMISS_IMAGE_LOCAL_BASE_URL . '/' . rawurlencode($base);
    }
}
