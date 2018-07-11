<?php

namespace Sinevia\Models\Tasks;

class Queue extends \App\Models\BaseModel {

    protected $table = 'snv_tasks_task';
    protected $primaryKey = 'Id';
    public static $statusList = [
        'Queued' => 'Queued',
        'Processing' => 'Processing',
        'Completed' => 'Completed',
        'Failed' => 'Failed',
        'Paused' => 'Paused',
        'Deleted' => 'Deleted',
    ];

    const TYPE_UPDATE_PREFIX_PRICING = 'UpdatePrefixPricing';

    public function appendDetails($message) {
        if (is_array($message) OR is_object($message)) {
            $message = json_encode($message);
        }
        //$details = $this->Details;
        //$newDetails = $details . "\n" . date('Y-m-d H:i:s') . ' : ' . $message;
        //$this->Details = $newDetails;
        //$this->save();
        $newDetails = "\n" . date('Y-m-d H:i:s') . ' : ' . $message;
        
        file_put_contents(storage_path('logs/' . $this->Details), $newDetails, FILE_APPEND);
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

    public static function process($type, $parameters = [], $linkedIds = []) {
        $task = new self;
        $task->Id = \Sinevia\Uid::microUid();
        $task->Type = $type;
        $task->Status = 'Processing';
        $task->Parameters = json_encode($parameters);
        $task->LinkedIds = json_encode($linkedIds);
        $task->Attempts = 0;
        $task->StartedAt = date('Y-m-d H:i:s');
        $task->Details = $task->Id . '.task.log.txt';
        $task->save();
        return $task;
    }

    public static function queue($type, $parameters = [], $linkedIds = []) {
        $task = new self;
        $task->Id = \Sinevia\Uid::microUid();
        $task->Type = $type;
        $task->Status = 'Queued';
        $task->Parameters = json_encode($parameters);
        $task->LinkedIds = json_encode($linkedIds);
        $task->Attempts = 0;
        $task->Details = $task->Id . '.task.log.txt';
        $task->save();
        return $task;
    }

    public static function tableCreate() {
        $o = new self;
        $statusKeys = array_keys(self::$statusList);

        if (\Schema::connection($o->connection)->hasTable($o->table) == false) {
            return \Schema::connection($o->connection)->create($o->table, function (\Illuminate\Database\Schema\Blueprint $table) use ($o, $statusKeys) {
                        $table->engine = 'InnoDB';
                        $table->string($o->primaryKey, 40)->primary();
                        $table->enum('Status', $statusKeys)->default('Queued');
                        $table->string('Type', 50);
                        $table->string('LinkedIds', 255)->default('');
                        $table->text('Parameters')->nullable();
                        $table->text('Output')->nullable();
                        $table->text('Details')->nullable();
                        $table->integer('Attempts')->default(0);
                        $table->datetime('StartedAt')->nullable();
                        $table->datetime('CompletedAt')->nullable();
                        $table->datetime('CreatedAt')->nullable();
                        $table->datetime('UpdatedAt')->nullable();
                        $table->datetime('DeletedAt')->nullable();
                        $table->index(['Status', 'Type']);
                    });
        }

        return true;
    }

    public static function tableDelete() {
        $o = new self;
        return \Schema::connection($o->connection)->drop($o->table);
    }

}
