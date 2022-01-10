@props(['route'=>"#" , 'model'=>null])
<!-- Edit Comment Modal-->
<div class="modal fade bd-example-modal-lg" id="commentModalEdit" tabindex="-1" role="dialog"
     aria-labelledby="commentModalOpen" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentModalEditLabel">Update Comment for
                    <strong>{{$model->name}}</strong></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="updateForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>Fill Out the Title Field and Body to continue...</p>
                    <input type="hidden" name="accessory_id" value="{{ $model->id }}">
                </div>
                <div class="form-group pr-3 pl-3">
                    <label class="font-weight-bold" for="title">Comment Title</label>
                    <input type="text"
                           class="form-control <?php if ($errors->has('title')) {?>border-danger<?php }?>"
                           name="title" id="updateTitle" placeholder="Comment Title">
                </div>
                <div class="form-group pl-3 pr-3">
                    <label
                        class="font-weight-bold <?php if ($errors->has('comment')) {?>border-danger<?php }?>"
                        for="comment_content">Notes</label>
                    <textarea name="comment" id="updateComment" class="form-control" rows="5"></textarea>

                </div>
                <div class="p-2 text-lg-right">
                    <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-green" type="button" id="commentUpload">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
