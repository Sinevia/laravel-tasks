<?php

namespace Sinevia\Tasks\Helpers;

class Links {

    public static function adminHome($queryData = []) {
        return action('\Sinevia\Tasks\Http\Controllers\TasksController@anyIndex') . self::buildQueryString($queryData);
    }

    private static function buildQueryString($queryData = []) {
        $queryString = '';
        if (count($queryData)) {
            $queryString = '?' . http_build_query($queryData);
        }
        return $queryString;
    }

}
