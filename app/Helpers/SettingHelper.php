<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (!function_exists('set_setting')) {
    /**
     * Set a setting value by key.
     *
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @param string|null $description
     * @return \App\Models\Setting
     */
    function set_setting(string $key, $value, string $type = 'string', ?string $description = null)
    {
        return Setting::set($key, $value, $type, $description);
    }
}
