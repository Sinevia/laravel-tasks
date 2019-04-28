<!-- START: Task Update Dialog -->
<style>
    #ModalTaskUpdate sup{
        font-size:11px;
    }
    #ModalTaskUpdate .modal-footer{
        display: table;
        width:100%;
    }
</style>
<div id="ModalTaskUpdate" class="modal fade" style="display:none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Task Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Title</label>
                    <input name="Title" class="form-control" />
                </div>
                <div class="form-group">
                    <label>Alias (used for command name)</label>
                    <input name="Alias" class="form-control" />
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="Decription" class="form-control"></textarea>
                </div>
                <input type="hidden" name="TaskId"></textarea>
            </div>
            <div class="modal-footer">
                <a id="modal-close" href="#" class="btn btn-info float-left" data-dismiss="modal">
                    <i class="fas fa-chevron-left"></i>
                    Cancel
                </a>
                <a id="modal-close" href="#" class="btn btn-danger float-right" data-dismiss="modal" onclick="taskUpdate();">
                    <i class="fas fa-minus-circle"></i>
                    Delete
                </a>
            </div>
        </div>
    </div>
</div>

<script>

    function showTaskUpdateModal(taskId) {
        $('#ModalTaskUpdate input[name=TaskId]').val(taskId);
        $('#ModalTaskUpdate').modal('show');
    }

    function taskUpdate() {
        var taskId = $('#ModalTaskUpdate input[name=TaskId]').val();
        var title = $('#ModalTaskCreate input[name=Title]').val();
        var alias = $('#ModalTaskCreate input[name=Alias]').val();
        var description = $('#ModalTaskCreate textarea[name=Description]').val();

        if (title == "") {
            alert('Title is required field');
            return fase;
        }
        if (alias == "") {
            alert('Name is required field');
            return fase;
        }

        var url = '<?php echo action('\Sinevia\Tasks\Http\Controllers\TasksController@anyQueueTaskUpdateAjax'); ?>?TaskId=' + taskId;
        $.ajax({// ajax call starts
            url: url,
            data: {
                TaskId: taskId,
                Title: title,
                Alias: alias,
                Description: description,
                _token: "<?php echo csrf_token(); ?>"
            },
            dataType: 'json'
        }).done(function (response) {
            // DEBUG: console.log(response)
            if (response.status === 'success') {
                $('#ModalTaskUpdate').modal('hide');
                window.location.href = window.location.href;
            } else {
                alert(response.message);
                $('#ModalTaskUpdate').modal('hide');
                window.location.href = window.location.href;
            }
        }).fail(function () {
            alert('Getting details failed');
            $('#ModalTaskUpdate').modal('hide');
        });

    }
</script>
<!-- END: Task Update Dialog -->
