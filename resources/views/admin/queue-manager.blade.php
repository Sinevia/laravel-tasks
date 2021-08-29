<?php if (View::exists(config('tasks.admin.layout-master'))) { ?>
    @extends(config('tasks.layout-master'))
<?php } ?>

@section('webpage_title', 'Queue Manager')

@section('webpage_header')
<h1>
    Queue Manager
    <button type="button" class="btn btn-primary float-right" onclick="showPageCreateModal();">
        <span class="fas fa-plus-sign"></span>
        Enqueue Task
    </button>
</h1>
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="<?php echo \Sinevia\Tasks\Helpers\Links::adminHome(); ?>">
            <i class="fa fa-dashboard"></i> Home
        </a>
    </li>
    <li class="breadcrumb-item active">
        <a href="<?php echo \Sinevia\Tasks\Helpers\Links::adminQueueManager(); ?>">
            Queue Manager
        </a>
    </li>
</ol>
@stop

@section('webpage_content')

@include('tasks::admin.shared-navigation')

<style>
    div#RecordList table tr td .btn {
        margin:0px 2px;
    }
</style>
<div class="card box-primary" id="RecordList">
    <div class="box-header with-border">
    </div>

    <div class="card-body">

        <!-- START: Records -->
        <template v-if="records == null">
            <span v-if="recordsLoading">
                Loading tasks ...
            </span>

            <template v-if="recordsLoading==false">
                <div>
                    No tasks found
                </div>
            </template>
        </template>

        <template v-if="records != null">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="text-align:center;">
                            Name,
                            Alias,
                            ID
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
                            Status
                        </th>
                        <th style="text-align:center;width:180px;">
                            Action
                        </th>
                </thead>
                <tbody>
                    <tr v-for="record in records">
                        <td>
                            <div style="color:#333;font-size: 14px;font-weight:bold;">
                                {% record.task_name %}
                            </div>                        
                            <div style="color:#333;font-size: 12px;font-style:italic;">
                                {% record.alias %}
                            </div>
                            <div style="color:#333;font-size: 12px;font-style:italic;">
                                created. {% record.created_at %}
                            </div>
                            <div style="color:#999;font-size: 10px;">
                                ref. {% record.id %}
                            </div>
                        </td>
                        <td style="text-align:center;vertical-align: middle;">
                            {% record.started_at %}
                        </td>
                        <td style="text-align:center;vertical-align: middle;">
                            {% record.completed_at %}
                        </td>
                        <td style="text-align:center;vertical-align: middle;">
                            {% record.elapsed_time %}
                        </td>
                        <td style="text-align:center;vertical-align: middle;">
                            {% record.status %}
                        </td>
                        <td style="text-align:center;vertical-align: middle;">
                            <button class="btn btn-sm btn-success" v-on:click="showTaskParametersModal(record.id);" title="Parameters">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list-task" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M2 2.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5V3a.5.5 0 0 0-.5-.5H2zM3 3H2v1h1V3z"/>
                                <path d="M5 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM5.5 7a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9zm0 4a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9z"/>
                                <path fill-rule="evenodd" d="M1.5 7a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5V7zM2 7h1v1H2V7zm0 3.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5H2zm1 .5H2v1h1v-1z"/>
                                </svg>
                            </button>
                            <button class="btn btn-sm btn-info" v-on:click="showTaskDetailsModal(record.id);" title="Details">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                </svg>
                            </button>
                            <button class="btn btn-sm btn-warning" v-on:click="showTaskRequeueModal(record.id);" title="Re-queue">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                                <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                                </svg>
                            </button>

                            <!--<template v-if="record.status==='Deleted'">-->
                            <button 
                                v-on:click="recordDeleteModalShow(record.id)"
                                class="btn btn-sm btn-danger"
                                title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                <path d="M1.293 1.293a1 1 0 0 1 1.414 0L8 6.586l5.293-5.293a1 1 0 1 1 1.414 1.414L9.414 8l5.293 5.293a1 1 0 0 1-1.414 1.414L8 9.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L6.586 8 1.293 2.707a1 1 0 0 1 0-1.414z"/>
                                </svg>
                            </button>
                            <!--</template>-->

                            <!--
                            <template v-if="record.status!=='Deleted'">
                                <button class="btn btn-sm btn-danger" 
                                        v-on:click="showQueueTaskDeketeModal(record.id);"
                                        title="Trash"
                                        >
                                    Trash
                                </button>
                            </template>
                            -->

                        </td>
                    </tr>
                </tbody>
            </table>
        </template>
        <!-- END: Records -->

        <br />
        <br />

    </div>
    <div class="card-footer">
        <!-- START: Pagination -->
        <template v-if="records!=null && records.length > 0">
            Rows per page:
            <div class="btn-group btn-perpage" role="group">
                <button id="btnGroupDrop1" type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {% recordsPerPage %}
                </button>
                <div class="dropdown-menu">
                    <template v-for="value in [10,25,50,100,200,300,400,500]">
                        <button type="button" class="dropdown-item" v-on:click="paginationPerPageChanged(value)">
                            {% value %}
                        </button>
                    </template>
                </div>
            </div>
            &nbsp;
            Page:
            <div style="display:inline-block">                
                <div class="pagination" _v-if="Math.ceil(dataRowsTotal/dataRowsPerPage)>1">
                    <span v-on:click="paginationPrevClicked()"
                          v-if="recordsCurrentPage>0"
                          class="page-item disabled"
                          style="cursor:pointer;"
                          >
                        <span class="page-link">
                            ‹
                        </span>
                    </span>
                    <div class="btn-group btn-perpage" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {% (parseInt(recordsCurrentPage)+1) %}
                        </button>
                        <div class="dropdown-menu" style="max-height:200px;overflow: auto;">
                            <template v-for="(page, index) in Math.ceil(recordsTotal/recordsPerPage)">
                                <button type="button" class="dropdown-item" v-on:click="paginationPageClicked((page-1))">
                                    {% parseInt(page) %}
                                </button>
                            </template>
                        </div>
                    </div>
                    <span v-on:click="paginationNextClicked()"
                          style="cursor:pointer;"
                          class="page-item disabled"
                          >
                        <span class="page-link">
                            ›
                        </span>
                    </span>
                </div>
            </div>
        </template>
        <!-- END: Pagination -->
    </div>


    @include('tasks::admin.queue-task-delete-modal')
    @include('tasks::admin.queue-task-requeue-modal')
    @include('tasks::admin.queue-task-details-modal')
    @include('tasks::admin.queue-task-parameters-modal')
</div>

<script>const $fetchLinksAjaxUrl = <?= json_encode($fetchLinksAjaxUrl) ?></script>
<script src="https://unpkg.com/vue@next"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    //const page = getUrlParam("page", "");
    const RecordList = {
        delimiters: ['{%', '%}'],
        data() {
            return {
                //pageNumber:page,
                recordCreateModal: {
                    title: ""
                },
                records: null,
                recordsLoading: null,
                recordsTotal: 0,
                recordsPerPage: 20,
                recordsCurrentPage: 0, // the page number cusrrently displayed
            };
        },
        mounted() {
            setTimeout(() => {
                $(() => this.recordsFetch());
            }, 2000);
        },
        methods: {
            recordDeleteModalShow(taskId) {
                $('#ModalQueueTaskDelete input[name=QueuedTaskId]').val(taskId);
                $('#ModalQueueTaskDelete').modal('show');
            },
            queueTaskDelete() {
                var taskId = $('#ModalQueueTaskDelete input[name=QueuedTaskId]').val();

                var url = '<?php echo action('\Sinevia\Tasks\Http\Controllers\TasksController@anyQueueTaskDeleteAjax'); ?>?QueuedTaskId=' + taskId;
                $.ajax({// ajax call starts
                    url: url,
                    data: {QueuedTaskId: taskId, _token: "<?php echo csrf_token(); ?>"},
                    dataType: 'json'
                }).done((response) => {
                    // DEBUG: console.log(response)
                    if (response.status === 'success') {
                        $('#ModalQueueTaskDelete').modal('hide');
                        //window.location.href = window.location.href;
                        return this.recordsFetch();
                    } else {
                        alert(response.message);
                        $('#ModalQueueTaskDelete').modal('hide');
                        //window.location.href = window.location.href;
                        return this.recordsFetch();
                    }
                }).fail(() => {
                    alert('Getting details failed');
                    $('#ModalQueueTaskDelete').modal('hide');
                });
            },
            showTaskDetailsModal(taskId) {
                var url = '<?php echo action('\Sinevia\Tasks\Http\Controllers\TasksController@anyQueueTaskDetailsAjax'); ?>?QueuedTaskId=' + taskId;
                $.ajax({// ajax call starts
                    url: url,
                    data: {QueuedTaskId: taskId, _token: "<?php echo csrf_token(); ?>"},
                    dataType: 'json'
                }).done((response) => {
                    // DEBUG: console.log(response)
                    if (response.status === 'success') {
                        $('#ModalTaskDetails textarea[name=Details]').val(response.data.Details);
                    } else {
                        alert(response.message);
                        $('#ModalTaskDetails').modal('hide');
                    }
                }).fail(function () {
                    alert('Getting details failed');
                    $('#ModalTaskDetails').modal('hide');
                });

                $('#ModalTaskDetails textarea').val('Loading details for task ref. ' + taskId + '...');
                $('#ModalTaskDetails').modal('show');

            },
            showTaskParametersModal(taskId) {
                var url = '<?php echo action('\Sinevia\Tasks\Http\Controllers\TasksController@anyQueueTaskParametersAjax'); ?>?QueuedTaskId=' + taskId;
                $.ajax({// ajax call starts
                    url: url,
                    data: {QueuedTaskId: taskId, _token: "<?php echo csrf_token(); ?>"},
                    dataType: 'json'
                }).done(function (response) {
                    // DEBUG: console.log(response)
                    if (response.status === 'success') {
                        $('#ModalTaskParameters textarea[name=Parameters]').val(response.data.Parameters);
                    } else {
                        alert(response.message);
                        $('#ModalTaskParameters').modal('hide');
                    }
                }).fail(function () {
                    alert('Getting details failed');
                    $('#ModalTaskParameters').modal('hide');
                });

                $('#ModalTaskParameters textarea').val('Loading details for task ref. ' + taskId + '...');
                $('#ModalTaskParameters').modal('show');

            },
            showTaskRequeueModal(taskId) {
                $('#ModalTaskRequeue input[name=QueuedTaskId]').val(taskId);
                window.autoreload = false;
                var url = '<?php echo action('\Sinevia\Tasks\Http\Controllers\TasksController@anyQueueTaskParametersAjax'); ?>?QueuedTaskId=' + taskId;
                $.ajax({
                    url: url,
                    data: {QueuedTaskId: taskId, _token: "<?php echo csrf_token(); ?>"},
                    dataType: 'json'
                }).done(function (response) {
                    // DEBUG: console.log(response)
                    if (response.status === 'success') {
                        $('#ModalTaskRequeue textarea[name=Parameters]').val(response.data.Parameters);
                        $('#ModalTaskRequeue').modal('show');
                    } else {
                        alert(response.message);
                        $('#ModalTaskRequeue').modal('hide');
                    }
                }).fail(function () {
                    alert('Getting parameters failed');
                });
            },
            taskRequeue() {
                var taskId = $('#ModalTaskRequeue input[name=QueuedTaskId]').val();
                var parameters = $('#ModalTaskRequeue textarea[name=Parameters]').val();
                var url = '<?php echo action('\Sinevia\Tasks\Http\Controllers\TasksController@anyQueueTaskRequeueAjax'); ?>?QueuedTaskId=' + taskId;
                $.ajax({// ajax call starts
                    url: url,
                    data: {QueuedTaskId: taskId, Parameters: parameters, _token: "<?php echo csrf_token(); ?>"},
                    dataType: 'json'
                }).done((response) => {
                    // DEBUG: console.log(response)
                    if (response.status === 'success') {
                        $('#ModalTaskRequeue').modal('hide');
                        //window.location.href = window.location.href;
                        return this.recordsFetch();
                    } else {
                        alert(response.message);
                        $('#ModalTaskRequeue').modal('hide');
                        //window.location.href = window.location.href;
                        return this.recordsFetch();
                    }
                }).fail(() => {
                    alert('Getting details failed');
                    $('#ModalTaskRequeue').modal('hide');
                });
                window.autoreload = true;
            },

            /**
             * Deletes a record
             */
            recordDelete(recordId) {
                $.get('/crud/record-delete-ajax', {
                    record_id: recordId
                }).then((response) => {
                    console.log(response);
                    if (response.status === "success") {
                        this.recordsFetch();
                        return true;
                    }

                    Swal.fire({title: 'Error!', text: response.message, icon: 'error', heightAuto: false});
                }).fail(() => {
                    Swal.fire({title: 'Error!', text: "Sorry there was an IO error, please visit this page later", icon: 'error', heightAuto: false});
                }).always(() => {
                    this.recordsLoading = false;
                });
            },

            /**
             * Fetches the record
             */
            recordsFetch() {
                this.recordsLoading = true;
                this.records = null;
                $.get($fetchLinksAjaxUrl, {
                    page: this.recordsCurrentPage,
                    per_page: this.recordsPerPage,
                }).then((response) => {
                    if (response.status === "success") {
                        this.records = response.data.tasks;
                        this.recordsTotal = response.data.total;
                        return true;
                    }

                    Swal.fire({title: 'Error!', text: response.message, icon: 'error', heightAuto: false});
                }).fail(() => {
                    Swal.fire({title: 'Error!', text: "Sorry there was an IO error, please visit this page later", icon: 'error', heightAuto: false});
                }).always(() => {
                    this.recordsLoading = false;
                });
            },
            paginationNextClicked() {
                if (this.recordsCurrentPage < Math.ceil(this.recordsTotal / this.recordsPerPage) - 1) {
                    this.recordsCurrentPage++;
                }
                this.recordsFetch();
            },
            paginationPageClicked(page) {
                this.recordsCurrentPage = page;
                this.recordsFetch();
            },
            paginationPrevClicked() {
                if (this.recordsCurrentPage > 0) {
                    this.recordsCurrentPage--;
                }
                this.recordsFetch();
            },
            paginationPerPageChanged(perPage) {
                this.recordsCurrentPage = 0;
                this.recordsPerPage = perPage;
                this.recordsFetch();
            },
        }
    };

    Vue.createApp(RecordList).mount('#RecordList');
</script>

@stop
