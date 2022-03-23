@props(['route'=>"#" , 'model'=>null , 'title'=>''])
<!-- comments Modal-->
<div class="modal fade bd-example-modal-lg" id="commentModalOpen" tabindex="-1" role="dialog"
     aria-labelledby="commentModalOpen" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentModalOpenLabel">Creating a New comment for
                    <strong>{{$model->name}}</strong></h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p>Fill Out the Title Field and Body to continue...</p>
                    <input type="hidden" name="{{lcfirst($title)}}_id" value="{{ $model->id }}">
                </div>
                <div class="form-group pr-3 pl-3">
                    <label class="font-weight-bold" for="title">Comment Title</label>
                    <input type="text" class="form-control <?php if ($errors->has('title')) {?>border-danger<?php }?>"
                           name="title" id="title" placeholder="Comment Title">
                </div>
                <div class="form-group pl-3 pr-3">
                    <label class="font-weight-bold <?php if ($errors->has('comment')) {?>border-danger<?php }?>"
                           for="comment_content">Notes</label>
                    <textarea name="comment" id="comment" class="form-control" rows="5"></textarea>

                </div>
                <div class="p-2 text-lg-right">
                    <button class="btn btn-grey" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-green" type="button" id="commentUpload">
                        Save
                    </button>
                </div>
                @csrf
            </form>
        </div>
    </div>
</div>
