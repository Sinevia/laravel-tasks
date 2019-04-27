<?php

namespace Sinevia\Tasks;

use Illuminate\Support\ServiceProvider;

class TasksServiceProvider extends ServiceProvider {

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot() {
        $this->publishes([
            dirname(__DIR__) . '/config/tasks.php' => config_path('tasks.php'),
        ]);
        $this->loadMigrationsFrom(dirname(__DIR__) . '/database/migrations');
        $this->loadViewsFrom(dirname(__DIR__) . '/resources/views', 'tasks');
        // $this->loadRoutesFrom(dirname(__DIR__).'/routes.php');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register() {
        //
    }

}
