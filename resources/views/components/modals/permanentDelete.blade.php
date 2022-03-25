@props(['model']){{-- This is the Modal for Permanently deleting model types --}}{{-- The model type is passed throug the Modal and is only referenced as text--}}

<!-- Delete Modal-->
<div class="modal fade bd-example-modal-lg" id="permDeleteModal" tabindex="-1" role="dialog"
     aria-labelledby="permDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="permDeleteModalLabel">Are you sure you want to permanently delete
                                                                  this {{$model}}? </h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <input id="model-id" type="hidden" value="">
                <p>Select "Delete" to remove this {{$model}} from the system.</p>
                <small class="text-coral">**Warning this is permanent and cannot be undone</small>
            </div>
            <div class="modal-footer">
                <button class="btn btn-grey" type="button" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-coral" type="button" id="confirmPermDelete">Delete</button>
            </div>
        </div>
    </div>
</div>
