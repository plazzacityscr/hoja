<?php

declare(strict_types=1);

if (!function_exists('app')) {
    /**
     * Return the Leaf instance
     *
     */
    function app(): Leaf\App
    {
        if (!(\Leaf\Config::getStatic('app'))) {
            \Leaf\Config::singleton('app', function () {
                return new \Leaf\App();
            });
        }

        return \Leaf\Config::get('app');
    }
}

if (!function_exists('_env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function _env($key, $default = null)
    {
        $env = array_merge(getenv() ?? [], $_ENV ?? []);
        $value = $env[$key] ??= null;

        if ($value === null) {
            return $default;
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

        if (strpos($value, '"') === 0 && strpos($value, '"') === strlen($value) - 1) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}

if (!function_exists('make')) {
    /**
     * Cache and use a class
     *
     * @template T of object
     * @param class-string<T>|T $service
     * @return T
     */
    function make($service)
    {
        if (is_string($service)) {
            $serviceName = $service;
            $service = (new $service());
        } else {
            $serviceName = get_class($service);
        }

        if (!\Leaf\Config::getStatic("classes.$serviceName")) {
            \Leaf\Config::singleton("classes.$serviceName", function () use ($service) {
                return $service;
            });
        }

        return \Leaf\Config::get("classes.$serviceName");
    }
}

if (!function_exists('rescue')) {
    /**
     * Run the given callback and return its result. If an exception occurs, report it and return the default value.
     *
     * @template T
     * @param  callable  $callback
     * @param  mixed  $default
     * @return T|mixed
     */
    function rescue(callable $callback, $default = null)
    {
        try {
            return $callback();
        } catch (Throwable $e) {
            return $default instanceof Closure ? $default($e) : $default;
        }
    }
}

if (!function_exists('blade')) {
    /**
     * Return Blade instance
     *
     * @return \Leaf\Blade
     */
    function blade(): \Leaf\Blade
    {
        if (!(\Leaf\Config::getStatic('blade'))) {
            $viewConfig = \Leaf\Config::get('view', []);
            \Leaf\Config::singleton('blade', function () use ($viewConfig) {
                return new \Leaf\Blade(
                    $viewConfig['views_path'] ?? __DIR__ . '/../views',
                    $viewConfig['cache_path'] ?? __DIR__ . '/../storage/cache/views'
                );
            });
        }

        return \Leaf\Config::get('blade');
    }
}

if (!function_exists('view')) {
    /**
     * Render a Blade view
     *
     * @param  string  $view
     * @param  array  $data
     * @return string
     */
    function view(string $view, array $data = []): string
    {
        return blade()->render($view, $data);
    }
}

if (!function_exists('asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string  $path
     * @return string
     */
    function asset(string $path): string
    {
        $baseUrl = _env('APP_URL', 'http://localhost:8000');

        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    /**
     * Generate a url for the application.
     *
     * @param  string  $path
     * @return string
     */
    function url(string $path = ''): string
    {
        $baseUrl = _env('APP_URL', 'http://localhost:8000');

        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('__')) {
    /**
     * Translate the given message.
     *
     * @param  string  $key
     * @param  array  $replace
     * @param  string  $locale
     * @return string
     */
    function __(string $key, array $replace = [], string $locale = null): string
    {
        // Simple translation stub - can be expanded later
        return $key;
    }
}
