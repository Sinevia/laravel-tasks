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
                <h5 class="modal-title">Edit Task</h5>
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
                    <label>Status</label>
                    <select name="Status" class="form-control">
                        <option></option>
                        <option value="<?php echo Sinevia\Tasks\Models\Task::STATUS_ACTIVE?>">Active</option>                        
                        <option value="<?php echo Sinevia\Tasks\Models\Task::STATUS_DISABLED?>">Disabled</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="Description" class="form-control"></textarea>
                </div>
                <input type="hidden" name="TaskId"></textarea>
            </div>
            <div class="modal-footer">
                <a id="modal-close" href="#" class="btn btn-info float-left" data-dismiss="modal">
                    <i class="fas fa-chevron-left"></i>
                    Cancel
                </a>
                <a id="modal-close" href="#" class="btn btn-success float-right" data-dismiss="modal" onclick="taskUpdate();">
                    <i class="fas fa-check-circle"></i>
                    Save
                </a>
            </div>
        </div>
    </div>
</div>

<script>

    function showTaskUpdateModal(taskId) {
        var url = '<?php echo action('\Sinevia\Tasks\Http\Controllers\TasksController@anyTaskAjax'); ?>?TaskId=' + taskId;
        $.ajax({// ajax call starts
            url: url,
            data: {TaskId: taskId, _token: "<?php echo csrf_token(); ?>"},
            dataType: 'json'
        }).done(function (response) {
            // DEBUG: console.log(response)
            if (response.status === 'success') {
                $('#ModalTaskUpdate input[name=Title]').val(response.data.Title);
                $('#ModalTaskUpdate input[name=Alias]').val(response.data.Alias);
                $('#ModalTaskUpdate select[name=Status]').val(response.data.Status);
                $('#ModalTaskUpdate textarea[name=Description]').val(response.data.Description);
            } else {
                alert(response.message);
                $('#ModalTaskUpdate').modal('hide');
            }
        }).fail(function () {
            alert('Updating task failed');
            $('#ModalTaskUpdate').modal('hide');
        });
        
        $('#ModalTaskUpdate input[name=TaskId]').val(taskId);
        $('#ModalTaskUpdate').modal('show');
    }

    function taskUpdate() {
        var taskId = $('#ModalTaskUpdate input[name=TaskId]').val();
        var title = $('#ModalTaskUpdate input[name=Title]').val();
        var alias = $('#ModalTaskUpdate input[name=Alias]').val();
        var status = $('#ModalTaskUpdate select[name=Status]').val();
        var description = $('#ModalTaskUpdate textarea[name=Description]').val();

        if (title == "") {
            alert('Title is required field');
            return fase;
        }
        if (alias == "") {
            alert('Name is required field');
            return fase;
        }

        var url = '<?php echo action('\Sinevia\Tasks\Http\Controllers\TasksController@anyTaskUpdateAjax'); ?>?TaskId=' + taskId;
        $.ajax({// ajax call starts
            url: url,
            data: {
                TaskId: taskId,
                Title: title,
                Alias: alias,
                Status: status,
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
