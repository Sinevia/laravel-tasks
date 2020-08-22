# Laravel Tasks #

A task queue managment system for Laravel. It has a visually frontend to create task definitions, see queued/failed/completed tasks, with option to requeue tasks.

## Features ##

- GUI frontend
- Task Queue

## Installation ##

```
composer require sinevia/laravel-tasks
php artisan migrate
php artisan vendor:publish
```

## Uninstall (est. 5 mins) ##

Removal of the package is a breeze:

composer remove sinevia/laravel-tasks

Optionally, delete the tasks tables (all which start with the snv_tasks_ prefix)

## Configuration ##

After running the vendor:publish command, the CMS settings will be published in the /config/tasks.php config file. Check these out, and modify according to your taste

## Route Settings ##

```
Route::group(['middleware'=>'superuser-only-middleware'], function(){
    AdvancedRoute::controller('/tasks', '\Sinevia\Tasks\Http\Controllers\TasksController');
});
```
