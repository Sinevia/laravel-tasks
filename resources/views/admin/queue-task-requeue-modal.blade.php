<!-- START: Task Requeue Dialog -->
<div id="ModalTaskRequeue" class="modal fade" style="display:none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Task Requeue</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Parameters</label>
                    <textarea name="Parameters" class="form-control" style="width:100%;height:100px;"></textarea>
                </div>
                <input type="hidden" name="QueuedTaskId"></textarea>
            </div>
            <div class="modal-footer" style="display:table;width: 100%;">
                <a id="modal-close" href="#" class="btn btn-info float-left" data-dismiss="modal">
                    <i class="fas fa-chevron-left"></i>
                    Cancel
                </a>
                <a id="modal-close" href="#" class="btn btn-success float-right" data-dismiss="modal" v-on:click="taskRequeue();">
                    <i class="fas fa-check-circle"></i>
                    Continue
                </a>
            </div>
        </div>
    </div>
</div>
<!-- END: Task Requeue Dialog -->
