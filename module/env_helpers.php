<?php

if(! function_exists('load_env')){
    function load_env(){
        $dotenv = Dotenv\Dotenv::createUnsafeImmutable(dirname(__DIR__));
        $dotenv->load();
    }
}

if (! function_exists('env')) {
    function env($key, $default = null)
    {
        $value = getenv($key);
        if ($value === false) {
            return value($default);
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;

            case 'false':
            case '(false)':
                return false;

            case 'empty':
            case '(empty)':
                return '';

            case 'null':
            case '(null)':
                return;
        }

        if (strlen($value) > 1 && starts_with($value, '"') && ends_with($value, '"')) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}

if (! function_exists('value')) {
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (! function_exists('starts_with')) {
    function starts_with($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle != '' && substr($haystack, 0, strlen($needle)) === (string) $needle) {
                return true;
            }

        }

        return false;
    }
}

if (! function_exists('ends_with')) {
    function ends_with($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if (substr($haystack, -strlen($needle)) === (string) $needle) {
                return true;
            }

        }

        return false;
    }
}

?>