<?php
$queuedCount = \Sinevia\Tasks\Models\Queue::where('Status', '<>', 'Deleted')->count();
$taskCount = \Sinevia\Tasks\Models\Task::where('Status', '<>', 'Deleted')->count();
?>
<ul class="nav nav-pills nav-fill bg-light" style="margin: 0px 0px 10px 0px;">
    <li class="nav-item">
        <?php $active = request()->fullUrl() == Sinevia\Tasks\Helpers\Links::adminHome() ? ' active' : ''; ?>
        <a class="nav-link {{ $active }}" href="<?php echo Sinevia\Tasks\Helpers\Links::adminHome(); ?>">Dashboard</a>
    </li>
    <li class="nav-item">
        <?php $active = request()->fullUrl() == Sinevia\Tasks\Helpers\Links::adminQueueManager() ? ' active' : ''; ?>
        <a class="nav-link {{ $active }}" href="<?php echo Sinevia\Tasks\Helpers\Links::adminQueueManager(); ?>">
            Queue
            <span class="badge badge-secondary""><?php echo $queuedCount; ?></span>
        </a>
    </li>
    <li class="nav-item">
        <?php $active = request()->fullUrl() == Sinevia\Tasks\Helpers\Links::adminTaskManager() ? ' active' : ''; ?>
        <a class="nav-link {{ $active }}" href="<?php echo Sinevia\Tasks\Helpers\Links::adminTaskManager(); ?>">
            Tasks
            <span class="badge badge-secondary"><?php echo $taskCount; ?></span>
        </a>
    </li>
</ul>
