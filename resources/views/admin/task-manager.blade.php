<?php if (View::exists(config('tasks.admin.layout-master'))) { ?>
    @extends(config('tasks.layout-master'))
<?php } ?>

@section('webpage_title', 'Page Manager')

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
    </div>
</div>

@stop
