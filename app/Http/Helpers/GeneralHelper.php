<?php

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Session\SessionManager;
use Illuminate\Session\Store;

if (!function_exists('app_name')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}

if (!function_exists('api_url')) {
    /**
     * Return the api url set in config
     *
     * @return string
     */
    function api_url()
    {
        return config('app.api_url');
    }
}

if (!function_exists('api_img_url')) {
    /**
     * Return the api url set in config
     *
     * @return string
     */
    function api_img_url()
    {
        return config('app.api_img_url');
    }
}

if (!function_exists('frontend_url')) {
    /**
     * Return the frontend url set in config
     *
     * @return string
     */
    function frontend_url()
    {
        return config('app.frontend_url');
    }
}

if (!function_exists('validate_api_url')) {
    /**
     * @param $url
     * @param string $type
     * @return string
     * Embeds the API base URL if not already embedded or proper url
     */
    function validate_api_url($url, $type = 'img')
    {
        $baseUrl = $type === 'api' ? api_url() : api_img_url();
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return $baseUrl . $url;
        }
        return $url;
    }
}

if (!function_exists('validate_frontend_url')) {
    /**
     * @param $url
     * @return string
     * Embeds the frontend base URL if not already embedded or proper url
     */
    function validate_frontend_url($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return frontend_url() . $url;
        }
        return $url;
    }
}

if (!function_exists('activity_preview_url')) {
    /**
     * @param $pId
     * @param $aId
     * @return string
     * returns the activity preview url
     */
    function activity_preview_url($pId, $aId)
    {
        return validate_frontend_url("/playlist/$pId/activity/$aId/preview/lti");
    }
}

if (!function_exists('api_v_url')) {
    /**
     * Return the base api url with version
     *
     * @return string
     */
    function api_v_url()
    {
        return api_url() . '/v1/admin';
    }
}

if (!function_exists('auth_user')) {
    /**
     * Get the auth user from session and return
     * @param string $key
     * @return Application|SessionManager|Store|mixed
     */
    function auth_user($key = '')
    {
        $user = session("auth_user");
        if ($key) {
            return $user[$key] ?? null;
        }
        return $user;
    }
}
