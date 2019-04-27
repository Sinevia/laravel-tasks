<!-- START: Task Requeue Dialog -->
<style>
    #ModalTaskDelete sup{
        font-size:11px;
    }
    #ModalTaskDelete .modal-footer{
        display: table;
        width:100%;
    }
</style>
<div id="ModalTaskDelete" class="modal fade" style="display:none;">
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
                    Cancel
                </a>
                <a id="modal-close" href="#" class="btn btn-success float-left" data-dismiss="modal" onclick="taskDelete();">
                    Continue
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    
    function showTaskDeketeModal(taskId) {
        $('#ModalTaskDelete input[name=QueuedTaskId]').val(taskId);
        $('#ModalTaskDelete').modal('show');
    }
    
    function taskDelete() {
        var taskId = $('#ModalTaskDelete input[name=QueuedTaskId]').val();
        
        var url = '<?php echo action('\Sinevia\Tasks\Http\Controllers\TasksController@anyTaskDeleteAjax'); ?>?QueuedTaskId=' + taskId;
        $.ajax({// ajax call starts
            url: url,
            data: {QueuedTaskId: taskId, _token: "<?php echo csrf_token(); ?>"},
            dataType: 'json'
        }).done(function (response) {
            // DEBUG: console.log(response)
            if (response.status === 'success') {
                $('#ModalTaskDelete').modal('hide');
                window.location.href = window.location.href;
            } else {
                alert(response.message);
                $('#ModalTaskDelete').modal('hide');
                window.location.href = window.location.href;
            }
        }).fail(function () {
            alert('Getting details failed');
            $('#ModalTaskDelete').modal('hide');
        });
        
    }
</script>
<!-- END: Task Requeue Dialog -->
