<!-- Comment Delete Modal-->
<div class="modal fade bd-example-modal-lg" id="removeComment" tabindex="-1" role="dialog"
     aria-labelledby="removeCommentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeCommentLabel">Are you sure you want to delete this Comment? </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <x-form.layout method="DELETE" class="deleteCommentForm">
                    <input id="comment-id" type="hidden" value="">
                </x-form.layout>
                <p>Select "Delete" to remove this comment.</p>
                <small class="text-danger">**Warning this is permanent. </small>
            </div>
            <div class="modal-footer">
                <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-coral" type="button" id="confirmCommentBtn">Delete</button>
            </div>
        </div>
    </div>
</div>
