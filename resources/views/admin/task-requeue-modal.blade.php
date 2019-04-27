<!-- START: Task Requeue Dialog -->
<style>
    #ModalTaskRequeue sup{
        font-size:11px;
    }
    #ModalTaskRequeue .modal-footer{
        display: table;
    }
    #ModalTaskRequeue .modal-lg {
        max-width: 80% !important;
    }
</style>
<div id="ModalTaskRequeue" class="modal fade" style="display:none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Task Requeue</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to requeue this task?
                <br />
                <br />
                This cannot be stopped once triggered
                <input type="hidden" name="QueuedTaskId"></textarea>
            </div>
            <div class="modal-footer">
                <a id="modal-close" href="#" class="btn btn-info float-left" data-dismiss="modal">
                    Cancel
                </a>
                <a id="modal-close" href="#" class="btn btn-success float-left" data-dismiss="modal" onclick="taskRequeue();">
                    Continue
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    
    function showTaskRequeueModal(taskId) {
        $('#ModalTaskRequeue input[name=QueuedTaskId]').val(taskId);
        $('#ModalTaskRequeue').modal('show');
    }
    
    function taskRequeue() {
        var taskId = $('#ModalTaskRequeue input[name=QueuedTaskId]').val();
        
        alert(taskId);
        
        var url = '<?php echo action('\Sinevia\Tasks\Http\Controllers\TasksController@anyTaskRequeue'); ?>?QueuedTaskId=' + taskId;
        $.ajax({// ajax call starts
            url: url,
            data: {QueuedTaskId: taskId, _token: "<?php echo csrf_token(); ?>"},
            dataType: 'json'
        }).done(function (response) {
            // DEBUG: console.log(response)
            if (response.status === 'success') {
                alert(response.message);
                $('#ModalTaskRequeue').modal('hide');
                window.location.href = window.location.href;
            } else {
                alert(response.message);
                $('#ModalTaskRequeue').modal('hide');
                window.location.href = window.location.href;
            }
        }).fail(function () {
            alert('Getting details failed');
            $('#ModalTaskRequeue').modal('hide');
        });
        
    }
</script>
<!-- END: Task Requeue Dialog -->