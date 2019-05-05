<!-- START: Task Create Dialog -->
<style>
    #ModalTaskCreate sup{
        font-size:11px;
    }
    #ModalTaskCreate .modal-footer{
        display: table;
        width:100%;
    }
</style>
<div id="ModalTaskCreate" class="modal fade" style="display:none;">
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
            </div>
            <div class="modal-footer">
                <a id="modal-close" href="#" class="btn btn-info float-left" data-dismiss="modal">
                    <i class="fas fa-chevron-left"></i>
                    Cancel
                </a>
                <a id="modal-close" href="#" class="btn btn-success float-right" data-dismiss="modal" onclick="taskCreate();">
                    <i class="fas fa-check-circle"></i>
                    Create
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    
    function showTaskCreateModal() {
        $('#ModalTaskCreate').modal('show');
    }
    
    function taskCreate() {
        var title = $('#ModalTaskCreate input[name=Title]').val();
        var alias = $('#ModalTaskCreate input[name=Alias]').val();
        
        if(title==""){
            alert('Title is required field');
            return fase;
        }
        if(alias==""){
            alert('Name is required field');
            return fase;
        }
        
        var url = '<?php echo action('\Sinevia\Tasks\Http\Controllers\TasksController@anyTaskCreateAjax'); ?>';
        $.ajax({// ajax call starts
            url: url,
            data: {Title: title, Alias: alias, _token: "<?php echo csrf_token(); ?>"},
            dataType: 'json'
        }).done(function (response) {
            // DEBUG: console.log(response)
            if (response.status === 'success') {
                $('#ModalTaskCreate').modal('hide');
                window.location.href = window.location.href;
            } else {
                alert(response.message);
                $('#ModalTaskCreate').modal('hide');
                window.location.href = window.location.href;
            }
        }).fail(function () {
            alert('Creating task failed');
            $('#ModalTaskCreate').modal('hide');
        });
        
    }
</script>
<!-- END: Task Create Dialog -->
