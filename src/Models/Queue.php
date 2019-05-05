<?php

namespace Sinevia\Tasks\Models;

class Queue extends BaseModel {

    protected $table = 'snv_tasks_queue';
    protected $primaryKey = 'Id';
    public static $statusList = [
        'Cancelled' => 'Cancelled',
        'Completed' => 'Completed',
        'Deleted' => 'Deleted',
        'Failed' => 'Failed',
        'Paused' => 'Paused',
        'Processing' => 'Processing',
        'Queued' => 'Queued',
    ];

    const STATUS_CANCELLED = 'Cancelled';
    const STATUS_COMPLETED = 'Completed';
    const STATUS_DELETED = 'Deleted';
    const STATUS_FAILED = 'Failed';
    const STATUS_PAUSED = 'Paused';
    const STATUS_PROCESSING = 'Processing';
    const STATUS_QUEUED = 'Queued';

    public function task() {
        return $this->belongsTo('Sinevia\Tasks\Models\Task', 'TaskId', 'Id');
    }

    public function appendDetails($message) {
        if (is_array($message) OR is_object($message)) {
            $message = json_encode($message);
        }

        $details = $this->Details;
        $newDetails = $details . "\n" . date('Y-m-d H:i:s') . ' : ' . $message;
        $this->Details = $newDetails;
        $this->save();
        //$newDetails = "\n" . date('Y-m-d H:i:s') . ' : ' . $message;        
        //file_put_contents(storage_path('logs/' . $this->Details), $newDetails, FILE_APPEND);
    }

    public function getChain() {
        $batchId = $this->getParameter('batch');
        $sequence = $this->getParameter('sequence');

        $batchList = Task::where('Parameters', 'LIKE', '%"batch":"' . $batchId . '"%')->get();

        $chain = [];

        foreach ($batchList as $task) {
            $listBatchId = $task->getParameter('batch');
            $listSequence = $task->getParameter('sequence');
            if ($listBatchId != $batchId) {
                continue;
            }
            if ($listSequence >= $sequence) {
                continue;
            }
            $chain[$listSequence] = $task;
        }

        return array_values($chain);
    }

    public function isChainSuccess() {
        $chain = $this->getChain();
        foreach ($chain as $task) {
            if ($task->Status != 'Success') {
                return false;
            }
        }
        return true;
    }

    public function getParameters() {
        $parameters = json_decode($this->Parameters, true);
        if ($parameters == false) {
            return [];
        }
        return $parameters;
    }

    public function setParameter($key, $value) {
        $parameters = $this->getParameters();
        $parameters[$key] = $value;
        $this->setParameters($parameters);
    }

    public function setParameters($parameters) {
        $this->Parameters = json_encode($parameters, JSON_PRETTY_PRINT);
        $this->save();
    }

    public function getParameter($key) {
        $parameters = $this->getParameters();
        if (isset($parameters[$key])) {
            return $parameters[$key];
        }
        return null;
    }

    public function getOutput() {
        return json_decode($this->Output, true);
    }

    public function setOutput($ouput) {
        $this->Output = json_encode($ouput, JSON_PRETTY_PRINT);
        $this->save();
    }

    public function getOutputKey($key) {
        $output = $this->getOutput();
        if (isset($output[$key])) {
            return $output[$key];
        }
        return null;
    }

    public function setOutputKey($key, $value) {
        $output = $this->getOutput();
        $output[$key] = $value;
        $this->setOutput($output);
    }

    public function fail($message = 'Failed') {
        if ($message != null) {
            $this->appendDetails($message);
        }
        $this->Status = 'Failed';
        $this->CompletedAt = date('Y-m-d H:i:s');
        $this->save();
    }

    public function complete($message = 'Success') {
        if ($message != null) {
            $this->appendDetails($message);
        }
        $this->Status = 'Success';
        $this->CompletedAt = date('Y-m-d H:i:s');
        $this->save();
    }

    public static function process($taskId, $parameters = [], $linkedIds = []) {
        $queuedTask = new self;
        $queuedTask->Id = \Sinevia\Uid::microUid();
        $queuedTask->TaskId = $type;
        $queuedTask->Status = self::STATUS_PROCESSING;
        $queuedTask->Parameters = json_encode($parameters);
        $queuedTask->LinkedIds = json_encode($linkedIds);
        $queuedTask->Attempts = 0;
        $queuedTask->StartedAt = date('Y-m-d H:i:s');
        $queuedTask->Details = $task->Id . '.task.log.txt';
        $queuedTask->save();
        return $queuedTask;
    }
    
    public static function enqueueTaskByAlias($taskAlias, $parameters = []){
        $task = Task::whereAlias(trim($taskAlias))->first();
        
        if($task==null){
            return null;
        }
        
        return self::enqueueTaskById($task->Id, $parameters);
    }

    /**
     * Queues a task by ID and returns the queued instance
     */
    public static function enqueueTaskById($taskId, $parameters = []) {
        $queuedTask = new self;
        $queuedTask->Id = \Sinevia\Uid::microUid();
        $queuedTask->TaskId = $taskId;
        $queuedTask->Status = self::STATUS_QUEUED;
        $queuedTask->Parameters = json_encode($parameters);
        $queuedTask->Attempts = 0;
        $queuedTask->Details = '';
        $queuedTask->save();

        $queuedTask->Details = $queuedTask->Id . '.task.log.txt';
        $queuedTask->save();

        return $queuedTask;
    }

    public static function tableCreate() {
        $o = new self;
        $statusKeys = array_keys(self::$statusList);

        if (\Schema::connection($o->connection)->hasTable($o->table) == false) {
            return \Schema::connection($o->connection)->create($o->table, function (\Illuminate\Database\Schema\Blueprint $table) use ($o, $statusKeys) {
                        $table->engine = 'InnoDB';
                        $table->string($o->primaryKey, 40)->primary();
                        $table->enum('Status', $statusKeys)->default(self::STATUS_QUEUED);
                        $table->string('TaskId', 40);
                        //$table->string('Type', 50);
                        //$table->string('LinkedIds', 255)->default('');
                        $table->text('Parameters')->nullable();
                        $table->text('Output')->nullable();
                        $table->text('Details')->nullable();
                        $table->integer('Attempts')->default(0);
                        $table->datetime('StartedAt')->nullable()->default(NULL);
                        $table->datetime('CompletedAt')->nullable()->default(NULL);
                        $table->datetime('CreatedAt')->nullable()->default(NULL);
                        $table->datetime('UpdatedAt')->nullable()->default(NULL);
                        $table->datetime('DeletedAt')->nullable()->default(NULL);
                        // $table->index(['Status', 'Type']);
                    });
        }

        return true;
    }

    public static function tableDelete() {
        $o = new self;
        return \Schema::connection($o->connection)->drop($o->table);
    }

}
