<!-- START: Task Queue Task Delete Dialog -->
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
                <a id="modal-close" href="#" class="btn btn-danger float-right" data-dismiss="modal" v-on:click="queueTaskDelete();">
                    <i class="fas fa-minus-circle"></i>
                    Delete
                </a>
            </div>
        </div>
    </div>
</div>
<!-- END: Task Queue Task Delete Dialog -->
