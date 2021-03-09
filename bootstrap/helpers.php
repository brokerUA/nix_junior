<?php

use Illuminate\Support\Arr;

if (! function_exists('update_query')) {
    function update_query(array $params = [], array $except = []): string
    {
        $query = array_merge(
            Arr::except(request()->all(), $except),
            $params
        );

        return url()->current() . '?' . http_build_query($query);
    }
}
