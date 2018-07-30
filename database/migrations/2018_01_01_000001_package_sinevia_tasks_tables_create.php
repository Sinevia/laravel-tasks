<?php
class PackageSineviaTasksTablesCreate extends Illuminate\Database\Migrations\Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Sinevia\Tasks\Models\Task::tableCreate();
        Sinevia\Tasks\Models\Queue::tableCreate();
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {        
        Sinevia\Tasks\Models\Queue::tableDelete();
        Sinevia\Tasks\Models\Task::tableDelete();
    }
}
