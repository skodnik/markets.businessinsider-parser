<?php

declare(strict_types=1);

if (!function_exists('env')) {
    /**
     * Возвращает значения переменной окружения при ее наличии
     *
     * @param $key
     * @param false $if_not_exist
     * @return mixed
     */
    function env($key, bool $if_not_exist = false): mixed
    {
        return $_ENV[$key] ?? $if_not_exist;
    }
}