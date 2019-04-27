<!-- START: Task Details Dialog -->
<style>
    #ModalTaskDetails sup{
        font-size:11px;
    }
    #ModalTaskDetails .modal-footer{
        display: table;
    }
    #ModalTaskDetails .modal-lg {
        max-width: 80% !important;
    }
</style>
<div id="ModalTaskDetails" class="modal fade" style="display:none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Task Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <textarea name="Details" class="form-control" style="width:100%;height:600px;"></textarea>
            </div>
        </div>
    </div>
</div>

<script>
    function showTaskDetailsModal(taskId) {
        var url = '<?php echo action('\Sinevia\Tasks\Http\Controllers\TasksController@anyTaskDetails'); ?>?QueuedTaskId=' + taskId;
        $.ajax({// ajax call starts
            url: url,
            data: {QueuedTaskId: taskId, _token: "<?php echo csrf_token(); ?>"},
            dataType: 'json'
        }).done(function (response) {
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
        
    }
</script>
<!-- END: Task Details Dialog -->
