@props(["archive"=>false])
<!-- Delete Modal-->
<div class="modal fade bd-example-modal-lg" id="removeUserModal" tabindex="-1" role="dialog"
     aria-labelledby="removeUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                @if($archive == false)
                    <h5 class="modal-title" id="removeUserModalLabel">Are you sure you want to send
                                                                      this {{ucfirst($slot)}}
                                                                      to
                                                                      the Recycle Bin? </h5>
                @else
                    <h5 class="modal-title" id="removeUserModalLabel">Are you sure you want to Permanently
                                                                      Delete this {{ucfirst($slot)}}? </h5>
                @endif

                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <input id="user-id" type="hidden" value="">
                @if($archive ==false)
                    <p>Select "Send to Bin" to send this {{ucfirst($slot)}} to the Recycle Bin.</p>
                    <small class="text-danger">**Warning this is not permanent and the {{ucfirst($slot)}} can be
                                               restored
                                               from
                                               the Recycle Bin. </small>
                @else
                    <p>Remove this item permanently from the system!</p>
                    <small class="text-danger">**Warning this is permanent. The {{ucfirst($slot)}} will be unassigned
                                               from
                                               any assets with Relationship's to this {{ucfirst($slot)}}.Relationship's
                                               will have their field's set to
                                               null.</small>
                @endif
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                @if($archive ==false)
                    <button class="btn btn-danger" type="button" id="confirmBtn">Send to Bin</button>
                @else
                    <button class="btn btn-danger" type="button" id="confirmBtn">Permanently Delete</button>

                @endif

            </div>
        </div>
    </div>
</div>

