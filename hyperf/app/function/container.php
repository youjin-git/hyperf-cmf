<?php

use \Hyperf\Utils\ApplicationContext;

if (!function_exists('di')) {
    function di()
    {
        return ApplicationContext::getContainer();
    }
}

if (!function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param string|null $abstract
     * @param array $parameters
     * @return
     */
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return ApplicationContext::getContainer();
        }
        return ApplicationContext::getContainer()->make($abstract, $parameters);
    }
}


function where2query($where, $query = null)
{
    if (!$where) {
        return $query;
    }
    $boolean = 'and';
    foreach ($where as $key => $item) {
        foreach ($item as $op => $val) {
            if ($op == 'between') {
                $query->whereBetween($key, $val, $boolean);
                continue;
            }
            if ($op == 'like') {
                $query->where($key, 'like', $val, $boolean);
                continue;
            }
        }
    }
    return $query;
}
