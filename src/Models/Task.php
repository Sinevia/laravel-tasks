<?php

namespace Sinevia\Tasks\Models;

class Task extends BaseModel {

    protected $table = 'snv_tasks_task';
    protected $primaryKey = 'Id';
    public static $statusList = [
        'Active' => 'Active',
        'Disabled' => 'Disabled',
    ];

    const STATUS_ACTIVE = 'Active';
    const STATUS_DISABLED = 'Disabled';
  
    public static function queue($type, $parameters = [], $linkedIds = []) {
        $queuedTas = new Queue;
        $queuedItem->Id = \Sinevia\Uid::microUid();
        $queuedItem->TaskId = $this->Id;
        $queuedItem->TaskAlias = $this->Alias;
        $queuedItem->Status = 'Queued';
        $queuedItem->Parameters = json_encode($parameters);
        $queue->LinkedIds = json_encode($linkedIds);
        $queue->Attempts = 0;
        $queue->Details = $task->Id . '.task.log.txt';
        $queue->save();
        return $queue;
    }

    public static function tableCreate() {
        $o = new self;
        $statusKeys = array_keys(self::$statusList);

        if (\Schema::connection($o->connection)->hasTable($o->table) == false) {
            return \Schema::connection($o->connection)->create($o->table, function (\Illuminate\Database\Schema\Blueprint $table) use ($o, $statusKeys) {
                        $table->engine = 'InnoDB';
                        $table->string($o->primaryKey, 40)->primary();
                        $table->enum('Status', $statusKeys)->default($o::STATUS_DISABLED);
                        $table->string('Alias', 50)->comment('Human readable short name, will be displayed in the queue table');
                        $table->string('Title', 255)->comment('Title of the task');
                        $table->text('Description')->nullable()->comment('Description of what the task does');
                        $table->text('Memo')->nullable()->comment('Reminder notes related to the task');
                        $table->datetime('CreatedAt')->nullable()->default(NULL);
                        $table->datetime('UpdatedAt')->nullable()->default(NULL);
                        $table->datetime('DeletedAt')->nullable()->default(NULL);
                        $table->index(['Status']);
                    });
        }

        return true;
    }

    public static function tableDelete() {
        $o = new self;
        return \Schema::connection($o->connection)->drop($o->table);
    }

}
