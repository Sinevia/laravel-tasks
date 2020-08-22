<!-- START: Task Update Dialog -->
<style>
    #ModalTaskEnqueue sup{
        font-size:11px;
    }
    #ModalTaskEnqueue .modal-footer{
        display: table;
        width:100%;
    }
</style>
<div id="ModalTaskEnqueue" class="modal fade" style="display:none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enqueue Task</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Parameters</label>
                    <textarea name="Parameters" class="form-control" style="width:100%;height:100px;"></textarea>
                </div>
                <input type="hidden" name="TaskId"></textarea>
            </div>
            <div class="modal-footer">
                <a id="modal-close" href="#" class="btn btn-info float-left" data-dismiss="modal">
                    <i class="fas fa-chevron-left"></i>
                    Cancel
                </a>
                <a id="modal-close" href="#" class="btn btn-success float-right" data-dismiss="modal" onclick="taskEnqueue();">
                    <i class="fas fa-check-circle"></i>
                    Enqueue
                </a>
            </div>
        </div>
    </div>
</div>

<script>

    function showTaskEnqueueModal (taskId) {
        $('#ModalTaskEnqueue input[name=TaskId]').val(taskId);
        $('#ModalTaskEnqueue textarea[name=Parameters]').val("{}");
        $('#ModalTaskEnqueue').modal('show');
    }

    function taskEnqueue() {
        var taskId = $('#ModalTaskEnqueue input[name=TaskId]').val();
        var parameters = $('#ModalTaskEnqueue textarea[name=Parameters]').val();

        var url = '<?php echo action('\Sinevia\Tasks\Http\Controllers\TasksController@anyTaskEnqueueAjax'); ?>';
        $.ajax({// ajax call starts
            url: url,
            data: {TaskId: taskId, Parameters: parameters, _token: "<?php echo csrf_token(); ?>"},
            dataType: 'json'
        }).done(function (response) {
            // DEBUG: console.log(response)
            if (response.status === 'success') {
                $('#ModalTaskEnqueue').modal('hide');
                window.location.href = '<?php echo \Sinevia\Tasks\Helpers\Links::adminQueueManager(); ?>';
            } else {
                alert(response.message);
                $('#ModalTaskEnqueue').modal('hide');
            }
        }).fail(function () {
            alert('Getting details failed');
            $('#ModalTaskEnqueue').modal('hide');
        });
    }
</script>
<!-- END: Task Update Dialog -->
