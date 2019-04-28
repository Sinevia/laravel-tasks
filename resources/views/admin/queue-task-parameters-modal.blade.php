<!-- START: Task Parameters Dialog -->
<style>
    #ModalTaskParameters sup{
        font-size:11px;
    }
    #ModalTaskParameters .modal-footer{
        display: table;
    }
    #ModalTaskParameters .modal-lg {
        max-width: 80% !important;
    }
</style>
<div id="ModalTaskParameters" class="modal fade" style="display:none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Task Parameters</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <textarea name="Parameters" class="form-control" style="width:100%;height:600px;"></textarea>
            </div>
        </div>
    </div>
</div>

<script>
    function showTaskParametersModal(taskId) {
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
        
    }
</script>
<!-- END: Task Parameters Dialog -->
