<?php

if (!function_exists("generate_url")) {
    /**
     * @param $addon_name
     * @param $controller
     * @param $action
     * @param array $params
     * @return string
     */
    function generate_url($addon_name, $controller, $action, array $params = [])
    {
        $params = array_filter($params, function ($item) { return !is_null($item); });
        $params["module"]      = $addon_name;
        $params["_controller"] = $controller;
        $params["_action"]     = $action;

        return "?" . http_build_query($params);
    }
}
