<ul class="nav nav-pills nav-fill bg-light" style="margin: 0px 0px 10px 0px;">
    <!--
    <li class="nav-item">
      <a class="nav-link active" href="#">Dashboard</a>
    </li>
    -->
    <li class="nav-item">
        <a class="nav-link" href="<?php echo Sinevia\Tasks\Helpers\Links::adminQueueManager(); ?>">
            Queue
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo Sinevia\Tasks\Helpers\Links::adminTaskManager(); ?>">
            Tasks
        </a>
    </li>
</ul>
