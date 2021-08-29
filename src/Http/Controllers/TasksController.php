<?php

namespace Sinevia\Tasks\Http\Controllers;

/**
 * Contains simple Task management functionality
 */
class TasksController extends \Illuminate\Routing\Controller {

    function anyIndex() {
        return $this->getQueueManager();
    }

    function getTaskManager() {
        $view = request('view');
        $session_order_by = \Session::get('tasks_task_manager_by', 'CreatedAt');
        $session_order_sort = \Session::get('tasks_task_manager_sort', 'DESC');
        $orderby = request('by', $session_order_by);
        $sort = request('sort', $session_order_sort);
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

        $query = \Sinevia\Tasks\Models\Task::getModel();
        $query = $query->orderBy($orderby, $sort);
        $tasks = $query->paginate(20);

        return view('tasks::admin/task-manager', get_defined_vars());
    }

    function getQueueManager() {
        $view = request('view');
        $session_order_by = \Session::get('tasks_queue_manager_by', 'CreatedAt');
        $session_order_sort = \Session::get('tasks_queue_manager_sort', 'DESC');
        $orderby = request('by', $session_order_by);
        $sort = request('sort', $session_order_sort);
        $page = request('page', 0);
        $results_per_page = 20;
        \Session::put('tasks_queue_manager_by', $orderby); // Keep for session
        \Session::put('tasks_queue_manager_sort', $sort);  // Keep for session

        $filterStatus = request('filter_status', '');
        $filterSearch = request('filter_search', '');
        if ($view == 'trash') {
            $filterStatus = 'Deleted';
        }
        if ($filterStatus == 'Deleted') {
            $view = 'trash';
        }

        $query = \Sinevia\Tasks\Models\Queue::getModel();
        $query = $query->orderBy($orderby, $sort);
        $queuedTasks = $query->paginate(20);

        $fetchLinksAjaxUrl = \Sinevia\Tasks\Helpers\Links::fetchTasksAjax();

        return view('tasks::admin/queue-manager', get_defined_vars());
    }

    function anyTaskAjax() {
        $taskId = request('TaskId');
        $task = \Sinevia\Tasks\Models\Task::find($taskId);

        if (is_null($task)) {
            return json_encode(['status' => 'error', 'message' => 'Task not found']);
        }

        return json_encode([
            'status' => 'success',
            'message' => 'Task found',
            'data' => [
                'Title' => $task->Title,
                'Alias' => $task->Alias,
                'Description' => $task->Description,
                'Status' => $task->Status,
            ]
        ]);
    }

    function anyTaskCreateAjax() {
        $title = request('Title');
        $alias = request('Alias');

        $taskWithAias = \Sinevia\Tasks\Models\Task::whereAlias($alias)->first();

        if ($title == "") {
            return json_encode(['status' => 'error', 'message' => 'Title is required field']);
        }

        if ($alias == "") {
            return json_encode(['status' => 'error', 'message' => 'Alias is required field']);
        }

        if ($taskWithAias != null) {
            return json_encode(['status' => 'error', 'message' => 'The alias already exists']);
        }

        $task = new \Sinevia\Tasks\Models\Task;
        $task->Title = $title;
        $task->Alias = $alias;

        $isSuccess = $task->save();

        if ($isSuccess) {
            return json_encode(['status' => 'success', 'message' => 'Task created successfully']);
        }

        return json_encode(['status' => 'error', 'message' => 'Task faied to be created']);
    }

    function anyTaskDeleteAjax() {
        $taskId = request('TaskId');
        $task = \Sinevia\Tasks\Models\Task::find($taskId);

        if (is_null($task)) {
            return json_encode(['status' => 'error', 'message' => 'Task not found']);
        }

        $isSuccess = $task->delete();

        if ($isSuccess) {
            return json_encode(['status' => 'success', 'message' => 'Task deleted']);
        }

        return json_encode(['status' => 'error', 'message' => 'Task faied to be deleted']);
    }

    function anyTaskEnqueueAjax() {
        $taskId = request('TaskId');
        $parameters = trim(request('Parameters'));

        $task = \Sinevia\Tasks\Models\Task::find($taskId);

        if (is_null($task)) {
            return json_encode(['status' => 'error', 'message' => 'Task not found']);
        }

        if ($task->Status != \Sinevia\Tasks\Models\Task::STATUS_ACTIVE) {
            return json_encode(['status' => 'error', 'message' => 'Task not active']);
        }

        if ($parameters == "") {
            return json_encode(['status' => 'error', 'message' => 'Parameters is required field']);
        }

        if (\Sinevia\StringUtils::isJson($parameters) == false) {
            return json_encode(['status' => 'error', 'message' => 'Parameters is not valid JSON']);
        }


        $isSuccess = \Sinevia\Tasks\Models\Queue::enqueueTaskById($taskId, json_decode($parameters, true));

        if ($isSuccess) {
            return json_encode(['status' => 'success', 'message' => 'Task enqueued']);
        }

        return json_encode(['status' => 'error', 'message' => 'Task faied to be enqueued']);
    }

    function anyTaskUpdateAjax() {
        $taskId = request('TaskId');
        $task = \Sinevia\Tasks\Models\Task::find($taskId);

        if (is_null($task)) {
            return json_encode(['status' => 'error', 'message' => 'Task not found']);
        }

        $title = request('Title');
        $alias = request('Alias');
        $status = request('Status');
        $description = request('Description');

        if ($title == "") {
            return json_encode(['status' => 'error', 'message' => 'Title is required field']);
        }

        if ($alias == "") {
            return json_encode(['status' => 'error', 'message' => 'Alias is required field']);
        }

        $task->Title = $title;
        $task->Alias = $alias;
        $task->Description = $description;
        $task->Status = $status;

        $isSuccess = $task->save();

        if ($isSuccess) {
            return json_encode(['status' => 'success', 'message' => 'Task updated successfully']);
        }

        return json_encode(['status' => 'error', 'message' => 'Task faied to be updated']);
    }

    function anyQueueTaskDeleteAjax() {
        $queuedTaskId = request('QueuedTaskId');
        $queuedTask = \Sinevia\Tasks\Models\Queue::find($queuedTaskId);

        if (is_null($queuedTask)) {
            return json_encode(['status' => 'error', 'message' => 'Queued task not found']);
        }

        $isSuccess = $queuedTask->delete();

        if ($isSuccess) {
            return json_encode(['status' => 'success', 'message' => 'Queued task deleted']);
        }

        return json_encode(['status' => 'error', 'message' => 'Queued task faied to be deleted']);
    }

    function anyQueueTasksFetchAjax() {
        $view = request('view');
        $session_order_by = \Session::get('tasks_queue_manager_by', 'CreatedAt');
        $session_order_sort = \Session::get('tasks_queue_manager_sort', 'DESC');
        $orderby = request('by', $session_order_by);
        $sort = request('sort', $session_order_sort);
        $page = request('page', 0);
        $results_per_page = request('per_page', 20);
        \Session::put('tasks_queue_manager_by', $orderby); // Keep for session
        \Session::put('tasks_queue_manager_sort', $sort);  // Keep for session

        $filterStatus = request('filter_status', '');
        $filterSearch = request('filter_search', '');
        if ($view == 'trash') {
            $filterStatus = 'Deleted';
        }
        if ($filterStatus == 'Deleted') {
            $view = 'trash';
        }

        $query = \Sinevia\Tasks\Models\Queue::getModel();
        $query = $query->orderBy($orderby, $sort);
        $queuedTasksTotal = $query->count();
        $queuedTasks = $query->paginate(20)->map(function($record) {
                    $taskName = is_null($record->task) ? 'n/a' : $record->task->Title;
                    $createdAtTime = trim($record->CreatedAt);
                    $startedAtTime = trim($record->StartedAt);
                    $completedAtTime = trim($record->CompletedAt);
                    $elapsedTime = 'n/a';
                    if ($startedAtTime != "" AND $startedAtTime != "") {
                        $elapsedTime = strtotime($completedAtTime) - strtotime($startedAtTime);
                    }
                    if ($completedAtTime != "") {
                        $completedAt = date('H:i:s', strtotime($completedAtTime));
                    } else {
                        $completedAt = 'n/a';
                    }
                    if ($startedAtTime != "") {
                        $startedAt = date('H:i:s', strtotime($startedAtTime));
                    } else {
                        $startedAt = 'n/a';
                    }
                    if ($createdAtTime != "") {
                        $createdAt = date('Y-m-d H:i:s', strtotime($createdAtTime));
                    } else {
                        $createdAt = 'n/a';
                    }
                    return[
                        'id' => $record->Id,
                        'task_name' => $taskName,
                        'status' => $record->Status,
                        'created_at' => $createdAt,
                        'started_at' => $startedAt,
                        'elapsed_time' => $elapsedTime,
                        'completed_at' => $completedAt,
                    ];
                })->toArray();

        return response()->json(['status' => 'success', 'message' => 'Tasks listed', 'data' => [
                        'tasks' => $queuedTasks,
                        'total' => $queuedTasksTotal,
        ]]);
    }

    function anyQueueTaskDetailsAjax() {
        $queuedTaskId = request('QueuedTaskId');
        $queuedTask = \Sinevia\Tasks\Models\Queue::find($queuedTaskId);

        if (is_null($queuedTask)) {
            return json_encode(['status' => 'error', 'message' => 'Task not found']);
        }

        return json_encode(['status' => 'success', 'message' => 'Task found', 'data' => ['Details' => $queuedTask->Details]]);
    }

    function anyQueueTaskParametersAjax() {
        $queuedTaskId = request('QueuedTaskId');
        $queuedTask = \Sinevia\Tasks\Models\Queue::find($queuedTaskId);

        if (is_null($queuedTask)) {
            return json_encode(['status' => 'error', 'message' => 'Task not found']);
        }

        return json_encode(['status' => 'success', 'message' => 'Task found', 'data' => ['Parameters' => $queuedTask->Parameters]]);
    }

    function anyQueueTaskRequeueAjax() {
        $queuedTaskId = trim(request('QueuedTaskId'));
        $parameters = trim(request('Parameters'));
        $queuedTask = \Sinevia\Tasks\Models\Queue::find($queuedTaskId);

        if (is_null($queuedTask)) {
            return json_encode(['status' => 'error', 'message' => 'Queued task not found']);
        }

        if ($parameters == "") {
            return json_encode(['status' => 'error', 'message' => 'Parameters is required field']);
        }

        if (\Sinevia\StringUtils::isJson($parameters) == false) {
            return json_encode(['status' => 'error', 'message' => 'Parameters is not valid JSON']);
        }

        if ($queuedTask->task == null) {
            return json_encode(['status' => 'error', 'message' => 'Task not found']);
        }

        $isSuccess = \Sinevia\Tasks\Models\Queue::enqueueTaskById($queuedTask->task->Id, json_decode($parameters, true));

        if ($isSuccess) {
            return json_encode(['status' => 'success', 'message' => 'Task requeued']);
        }

        return json_encode(['status' => 'error', 'message' => 'Task faied to be requeued']);
    }

}
