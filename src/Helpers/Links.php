<?php

namespace Sinevia\Tasks\Helpers;

class Links {

    public static function adminHome($queryData = []) {
        return action('\Sinevia\Tasks\Http\Controllers\TasksController@anyIndex') . self::buildQueryString($queryData);
    }
    
    public static function adminQueueManager($queryData = []) {
        return action('\Sinevia\Tasks\Http\Controllers\TasksController@getQueueManager') . self::buildQueryString($queryData);
    }
    
    public static function adminTaskManager($queryData = []) {
        return action('\Sinevia\Tasks\Http\Controllers\TasksController@getTaskManager') . self::buildQueryString($queryData);
    }
    
    public static function fetchTasksAjax($queryData = []) {
        return action('\Sinevia\Tasks\Http\Controllers\TasksController@anyQueueTasksFetchAjax') . self::buildQueryString($queryData);
    }

    private static function buildQueryString($queryData = []) {
        $queryString = '';
        if (count($queryData)) {
            $queryString = '?' . http_build_query($queryData);
        }
        return $queryString;
    }

}
