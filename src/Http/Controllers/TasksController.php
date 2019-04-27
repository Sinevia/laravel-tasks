<?php

namespace Sinevia\Tasks\Http\Controllers;

/**
 * Contains simple Task management functionality
 */
class TasksController extends \Illuminate\Routing\Controller {

    function anyIndex() {
        return $this->getTaskManager();
    }

    function getTaskManager() {
        $view = request('view');
        $session_order_by = \Session::get('tasks_task_manager_by', 'Title');
        $session_order_sort = \Session::get('tasks_task_manager_sort', 'asc');
        $orderby = request('by', $session_order_by);
        $sort = request('sort');
        $page = request('page', 0);
        $results_per_page = 20;
        \Session::put('tasks_task_manager_by', $orderby); // Keep for session
        \Session::put('tasks_task_manager_sort', $sort);  // Keep for session

        $filterStatus = request('filter_status', '');
        $filterSearch = request('filter_search', '');
        if ($view == 'trash') {
            $filterStatus = 'Deleted';
        }
        if ($filterStatus == 'Deleted') {
            $view = 'trash';
        }
        
        $query = \Sinevia\Tasks\Models\Queue::getModel();
        $queuedTasks = $query->paginate(20);

        return view('tasks::admin/task-manager', get_defined_vars());
    }

}
