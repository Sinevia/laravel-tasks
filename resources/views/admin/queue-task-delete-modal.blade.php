<!-- START: Task Queue Task Delete Dialog -->
<style>
    #ModalQueueTaskDelete sup{
        font-size:11px;
    }
    #ModalQueueTaskDelete .modal-footer{
        display: table;
        width:100%;
    }
</style>
<div id="ModalQueueTaskDelete" class="modal fade" style="display:none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Task Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this task?
                <br />
                <br />
                This cannot be stopped once triggered
                <input type="hidden" name="QueuedTaskId"></textarea>
            </div>
            <div class="modal-footer">
                <a id="modal-close" href="#" class="btn btn-info float-left" data-dismiss="modal">
                    <i class="fas fa-chevron-left"></i>
                    Cancel
                </a>
                <a id="modal-close" href="#" class="btn btn-danger float-right" data-dismiss="modal" onclick="queueTaskDelete();">
                    <i class="fas fa-minus-circle"></i>
                    Delete
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    
    function showQueueTaskDeketeModal(taskId) {
        $('#ModalQueueTaskDelete input[name=QueuedTaskId]').val(taskId);
        $('#ModalQueueTaskDelete').modal('show');
    }
    
    function queueTaskDelete() {
        var taskId = $('#ModalQueueTaskDelete input[name=QueuedTaskId]').val();
        
        var url = '<?php echo action('\Sinevia\Tasks\Http\Controllers\TasksController@anyQueueTaskDeleteAjax'); ?>?QueuedTaskId=' + taskId;
        $.ajax({// ajax call starts
            url: url,
            data: {QueuedTaskId: taskId, _token: "<?php echo csrf_token(); ?>"},
            dataType: 'json'
        }).done(function (response) {
            // DEBUG: console.log(response)
            if (response.status === 'success') {
                $('#ModalQueueTaskDelete').modal('hide');
                window.location.href = window.location.href;
            } else {
                alert(response.message);
                $('#ModalQueueTaskDelete').modal('hide');
                window.location.href = window.location.href;
            }
        }).fail(function () {
            alert('Getting details failed');
            $('#ModalQueueTaskDelete').modal('hide');
        });
        
    }
</script>
<!-- END: Task Queue Task Delete Dialog -->
