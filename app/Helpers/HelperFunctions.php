<?php

if (!function_exists('fn_get_request_json_arr')) {
    /**
     * Get request json
     *
     * @return array|mixed
     */
    function fn_get_request_json_arr()
    {
        $requestBody = file_get_contents('php://input');
        $requestArr = json_decode($requestBody, TRUE);
        if (!is_array($requestArr)) {
            $requestArr = \Illuminate\Support\Facades\Request::all();
        }

        return $requestArr;
    }
}