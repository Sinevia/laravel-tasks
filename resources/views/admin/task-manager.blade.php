<?php if (View::exists(config('tasks.admin.layout-master'))) { ?>
    @extends(config('tasks.layout-master'))
<?php } ?>

@section('webpage_title', 'Task Manager')

@section('webpage_header')
<h1>
    Task Manager
    <button type="button" class="btn btn-primary pull-right" onclick="showPageCreateModal();">
        <span class="glyphicon glyphicon-plus-sign"></span>
        Queue Task
    </button>
</h1>
<ol class="breadcrumb">
    <li><a href="<?php echo \Sinevia\Tasks\Helpers\Links::adminHome(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active"><a href="<?php echo \Sinevia\Tasks\Helpers\Links::adminHome(); ?>">Tasks</a></li>
</ol>
@stop

@section('webpage_content')

@include('tasks::admin.shared-navigation')

<div class="box box-primary">
    <div class="box-header with-border">
    </div>

    <div class="box-body">

        <ul class="nav nav-tabs" style="margin-bottom: 3px;">
            <li class="<?php if ($view == '') { ?>active<?php } ?>">
                <a href="?view=all">
                    <span class="glyphicon glyphicon-list"></span> Live
                </a>
            </li>
            <li class="<?php if ($view == 'trash') { ?>active<?php } ?>">
                <a href="?&view=trash">
                    <span class="glyphicon glyphicon-trash"></span> Trash
                </a>
            </li>
        </ul>

        <table id="table_articles" class="table table-striped">
            <tr>
                <th style="text-align:center;">
                    <a href="?cmd=pages-manager&amp;by=Title&amp;sort=<?php if ($sort == 'asc') { ?>desc<?php } else { ?>asc<?php } ?>">
                        Name&nbsp;<?php
                        if ($orderby === 'Title') {
                            if ($sort == 'asc') {
                                ?>&#8595;<?php } else { ?>&#8593;<?php
                            }
                        }
                        ?>
                    </a>,
                    <a href="?cmd=pages-manager&amp;by=Alias&amp;sort=<?php if ($sort == 'asc') { ?>desc<?php } else { ?>asc<?php } ?>">
                        Alias&nbsp;<?php
                        if ($orderby === 'Alias') {
                            if ($sort == 'asc') {
                                ?>&#8595;<?php } else { ?>&#8593;<?php
                            }
                        }
                        ?>
                    </a>,
                    <a href="?cmd=pages-manager&amp;by=id&amp;sort=<?php if ($sort == 'asc') { ?>desc<?php } else { ?>asc<?php } ?>">
                        ID&nbsp;<?php
                        if ($orderby === 'Id') {
                            if ($sort == 'asc') {
                                ?>&#8595;<?php } else { ?>&#8593;<?php
                            }
                        }
                        ?>
                    </a>
                </th>
                <th>
                    Start Time
                </th>
                <th>
                    End Time
                </th>
                <th>
                    Elapsed Time
                </th>
                <th style="text-align:center;width:100px;">
                    <a href="?cmd=pages-manager&amp;by=Status&amp;sort=<?php if ($sort == 'asc') { ?>desc<?php } else { ?>asc<?php } ?>">
                        Status&nbsp;<?php
                        if ($orderby === 'Status') {
                            if ($sort == 'asc') {
                                ?>&#8595;<?php } else { ?>&#8593;<?php
                            }
                        }
                        ?>
                    </a>
                </th>
                <th style="text-align:center;width:160px;">Action</th>
            </tr>

            <?php foreach ($queuedTasks as $qt) { ?>
                <?php
                $createdAtTime = trim($qt->StartedAt);
                $startedAtTime = trim($qt->StartedAt);
                $completedAtTime = trim($qt->CompletedAt);
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
                ?>
                <tr>
                    <td>
                        <div style="color:#333;font-size: 14px;font-weight:bold;">
                            <?php echo $qt->Type; ?>
                        </div>                        
                        <div style="color:#333;font-size: 12px;font-style:italic;">
                            <?php echo $qt->Alias; ?>
                        </div>
                        <div style="color:#333;font-size: 12px;font-style:italic;">
                            created. <?php echo $createdAt; ?>
                        </div>
                        <div style="color:#999;font-size: 10px;">
                            ref. <?php echo $qt->Id; ?>
                        </div>
                    </td>
                    <td style="text-align:center;vertical-align: middle;">
                        <?php echo $startedAt; ?>
                    </td>
                    <td style="text-align:center;vertical-align: middle;">
                        <?php echo $completedAt; ?>
                    </td>
                    <td style="text-align:center;vertical-align: middle;">
                        <?php echo $elapsedTime; ?><br>
                    </td>
                    <td style="text-align:center;vertical-align: middle;">
                        <?php echo $qt->Status; ?><br>
                    </td>
                    <td style="text-align:center;vertical-align: middle;">
                        <a href="<?php echo $qt->Url; ?>" class="btn btn-sm btn-success" target="_blank">
                            <span class="glyphicon glyphicon-eye-open"></span>
                            Details
                        </a>
                        <a href="<?php echo \Sinevia\Tasks\Helpers\Links::adminHome(['PageId' => $qt->Id]); ?>" class="btn btn-sm btn-warning">
                            <span class="glyphicon glyphicon-edit"></span>
                            Edit
                        </a>
                        <?php if ($qt->Status == 'Deleted') { ?>
                            <button class="btn btn-sm btn-danger" onclick="confirmPageDelete('<?php echo $qt->Id; ?>');">
                                <span class="glyphicon glyphicon-remove-sign"></span>
                                Delete
                            </button>
                        <?php } ?>

                        <?php if ($qt->Status != 'Deleted') { ?>
                            <button class="btn btn-sm btn-danger" onclick="confirmPageMoveToTrash('<?php echo $qt->Id; ?>');">
                                <span class="glyphicon glyphicon-trash"></span>
                                Trash
                            </button>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>

        </table>

        <!-- START: Pagination -->    
        {!! $pages->render() !!}
        <!-- END: Pagination -->

    </div>
</div>

@stop
