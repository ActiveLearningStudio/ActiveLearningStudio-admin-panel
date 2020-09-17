<?php

if (! function_exists('app_name')) {
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

if (! function_exists('api_url')) {
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

if (! function_exists('frontend_url')) {
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

if (! function_exists('validate_api_url')){
    /**
     * @param $url
     * @return string
     * Embeds the API base URL if not already embedded
     */
    function validate_api_url($url){
        if (strpos($url, api_url()) === false){
            return api_url() . $url;
        }
        return $url;
    }
}

if (! function_exists('validate_frontend_url')){
    /**
     * @param $url
     * @return string
     * Embeds the forntend base URL if not already embedded
     */
    function validate_frontend_url($url){
        if (strpos($url, frontend_url()) === false){
            return frontend_url() . $url;
        }
        return $url;
    }
}

if (! function_exists('activity_preview_url')){
    /**
     * @param $pId
     * @param $aId
     * @return string
     * returns the activity preview url
     */
    function activity_preview_url($pId, $aId){
        return validate_frontend_url("/playlist/$pId/activity/$aId/preview/lti");
    }
}

