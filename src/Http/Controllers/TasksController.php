<?php

namespace Sinevia\Tasks\Http\Controllers;
/**
 * Contains simple Task management functionality
 */
class TasksController extends \Illuminate\Routing\Controller {
    function anyIndex(){
        return $this->getTaskManager();
    }
    
    function getTaskManager(){
        return view('tasks::admin/task-manager', get_defined_vars());
    }
}
