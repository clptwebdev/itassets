<div class="modal-header">
    <h5 class="modal-title" id="userPermissionsModalLabel">{{ $user->name}} has permissions for the following locations!
    </h5>
    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <input id="user-id" type="hidden" value="">
    <p>Select "Delete" to remove this User from the system.</p>
    <small class="text-danger">**Warning this is permanent. </small>
</div>
<div class="modal-footer">
    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
    <button class="btn btn-danger" type="button" id="confirmBtn">Save</button>
</div>