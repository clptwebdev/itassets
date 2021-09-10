@extends('layouts.app')

@section('title', "View Asset {$asset->asset_tag}")

@section('css')
<link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">View Asset</h1>
        <div>
            <a href="{{ route('assets.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                    class="fas fa-chevron-left fa-sm text-white-50"></i> Back</a>
            @can('generatePDF', $asset)
            <a href="{{ route('asset.showPdf', $asset->id)}}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm loading"><i
                        class="fas fa-file-pdf fa-sm text-white-50"></i> Generate Report</a>
            @endcan
            @can('edit', $asset)
            <a href="{{ route('assets.edit', $asset->id)}}"
               class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm"><i
                    class="fas fa-edit fa-sm text-white-50"></i> Edit</a>
            @endcan
            <form class="d-inline-block id="form{{$asset->id}}" action="{{ route('assets.destroy', $asset->id) }}"
                method="POST">
            @csrf
            @method('DELETE')
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm deleteBtn" data-id="{{$asset->id}}"><i
                    class="fas fa-trash fa-sm text-white-50"></i> Delete</a>
            </form>
        </div>
    </div>

    @if(session('danger_message'))
        <div class="alert alert-danger"> {!! session('danger_message')!!} </div>
    @endif

    @if(session('success_message'))
        <div class="alert alert-success"> {!! session('success_message')!!} </div>
    @endif

    <div class="row row-eq-height">
        <x-assets.asset-modal :asset="$asset" />
        <x-assets.asset-purchase :asset="$asset" />
    </div>

    <div class="row row-eq-height">
        <div class="col-12 col-lg-8 mb-4">
            <x-locations.location-modal :asset="$asset"/>
        </div>
        
        <div class="col-12 col-lg-4 mb-4">
            <x-manufacturers.manufacturer-modal :asset="$asset->model"/>
        </div>
        
    </div>
    <div class="row row-eq-height">
        <x-assets.asset-log :asset="$asset"/>
        <div class="col-12 col-lg-6 mb-4">
            <x-comments.comment-layout :asset="$asset"/>
        </div>   
    </div>
    

@endsection

@section('modals')
    <!-- asset Status Model Modal-->
    <div class="modal fade bd-example-modal-lg" id="assetModalStatus" tabindex="-1" role="dialog"
         aria-labelledby="assetModalStatusLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assetModalStatusLabel">Change Status
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('change.status', $asset->id)}}" method="post">
                <div class="modal-body">
                    @csrf
                    <select name="status" class="form-control">
                        @foreach(\App\Models\Status::all() as $status)
                        <option value="{{ $status->id}}" @if($status->id == $asset->status_id){{ 'selected'}}@endif>{{ $status->name }}</option>  
                        @endforeach  
                    </select> 
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-success" type="submit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- asset Delete Modal-->
    <div class="modal fade bd-example-modal-lg" id="removeAssetModal" tabindex="-1" role="dialog"
         aria-labelledby="removeAssetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeAssetModalLabel">Are you sure you want to delete this item?
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input id="asset-id" type="hidden" value="">
                    <p>Select "Delete" to remove this item from the system.</p>
                    <small class="text-danger">**Warning this is permanent. All assigned items will be
                        set to Null.</small>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" type="button" id="confirmBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- comments Modal-->
    <div class="modal fade bd-example-modal-lg" id="commentModalOpen" tabindex="-1" role="dialog" aria-labelledby="commentModalOpen" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalOpen2">Creating a New comment for
                        <strong>{{$asset->name}}</strong></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('asset.comment') }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>Fill Out the title Field and Body to continue...</p>
                        <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                        @csrf
                        <div class="form-group pr-3 pl-3">
                            <label class="font-weight-bold" for="title">Comment Title</label>
                            <input type="text"
                                class="form-control <?php if ($errors->has('title')) {?>border-danger<?php }?>"
                                name="title" id="title" placeholder="Comment Title">
                        </div>
                        <div class="form-group pl-3 pr-3">
                            <label
                                class="font-weight-bold <?php if ($errors->has('comment')) {?>border-danger<?php }?>"
                                for="comment">Notes</label>
                            <textarea name="comment" id="content" class="form-control" rows="5"></textarea>

                        </div>
                        <div class="p-2 text-lg-right">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success" type="button" id="commentUpload">
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Comment Modal-->
    <div class="modal fade bd-example-modal-lg" id="commentModalEdit" tabindex="-1" role="dialog"
         aria-labelledby="commentModalOpen" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalEditLabel">Update Comment for
                        <strong>{{$asset->model->name}}</strong></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="updateForm" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>Fill Out the Title Field and Body to continue...</p>
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="accessory_id"  value="{{ $asset->id }}">
                        <div class="form-group pr-3 pl-3">
                            <label class="font-weight-bold" for="title">Comment Title</label>
                            <input type="text" class="form-control <?php if ($errors->has('title')) {?>border-danger<?php }?>" name="title" id="updateTitle" placeholder="Comment Title">
                        </div>
                        <div class="form-group pl-3 pr-3">
                            <label
                                class="font-weight-bold <?php if ($errors->has('comment')) {?>border-danger<?php }?>"
                                for="comment_content">Notes</label>
                            <textarea name="comment" id="updateComment" class="form-control" rows="5"></textarea>

                        </div>
                        <div class="p-2 text-lg-right">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success" type="button" id="commentUpload">
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

     <!-- Comment Delete Modal-->
     <div class="modal fade bd-example-modal-lg" id="removeComment" tabindex="-1" role="dialog" aria-labelledby="removeCommentLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeCommentLabel">Are you sure you want to delete this Comment?
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input id="comment-id" type="hidden" value="">
                    <p>Select "Delete" to remove this comment.</p>
                    <small class="text-danger">**Warning this is permanent. </small>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" type="button" id="confirmCommentBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script>
        $('.deleteBtn').click(function () {
            $('#asset-id').val($(this).data('id'))
            //showModal
            $('#removeassetModal').modal('show')
        });

        $('#confirmBtn').click(function () {
            var form = '#' + 'form' + $('#asset-id').val();
            $(form).submit();
        });

        $('#commentModal').click(function () {
            //showModal
            $('#commentModalOpen').modal('show')
        });

        $('.editComment').click(function(event){
            event.preventDefault();
            $('#updateTitle').val($(this).data('title'));
            $('#updateComment').val($(this).data('comment'));
            var route = $(this).data('route');
            $('#updateForm').attr('action', route); 
            $('#commentModalEdit').modal('show');
        });

        $('.deleteComment').click(function () {
            $('#comment-id').val($(this).data('id'));
            //showModal
            $('#removeComment').modal('show');
        });

        $('#confirmCommentBtn').click(function () {
            var form = '#' + 'comment' + $('#comment-id').val();
            $(form).submit();
        });

        $(document).ready( function () {
            $('#comments').DataTable({
                "autoWidth": false,
                "pageLength": 10,
                "searching": false,
                "bLengthChange": false,
                "columnDefs": [ {
                    "targets": [1],
                    "orderable": false
                }],
                "order": [[ 0, "desc"]],
            });
        });
            

    </script>

@endsection
