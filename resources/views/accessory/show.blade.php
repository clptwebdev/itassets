@extends('layouts.app')

@section('title', 'View '.$accessory->name)

@section('css')
<link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')
    <form id="form{{$accessory->id}}" action="{{ route('accessories.destroy', $accessory->id) }}" method="POST">
        @csrf
        @method('DELETE')

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">View Accessory</h1>
            <div>
                <a href="{{ route('accessories.index')}}"
                   class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                        class="fas fa-chevron-left fa-sm text-dark-50"></i> Back</a>
                @can('delete', $accessory)
                <a class="d-none d-sm-inline-block btn btn-sm btn-coral shadow-sm deleteBtn" href="#"
                   data-id="{{$accessory->id}}"><i class=" fas fa-trash fa-sm text-white-50"></i> Delete</a>
                @endcan
                @can('update', $accessory)
                <a href="{{ route('accessories.edit', $accessory->id)}}"
                   class="d-none d-sm-inline-block btn btn-sm btn-yellow shadow-sm"><i
                        class="fas fa-edit fa-sm text-dark-50"></i> Edit</a>
                @endcan
                @can('export', $accessory)
                <a href="{{ route('accessories.showPdf', $accessory->id)}}" class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm loading"><i
                        class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                @endcan
            </div>
        </div>
    </form>

    @if(session('danger_message'))
        <div class="alert alert-danger"> {!! session('danger_message')!!} </div>
    @endif

    @if(session('success_message'))
        <div class="alert alert-success"> {!! session('success_message')!!} </div>
    @endif
    <section class="m-auto">
        <p class="mb-4">Information regarding {{ $accessory->name }} including the location and any comments made by staff. </p>

        <div class="row row-eq-height">
            <x-accessories.accessory-info :accessory="$accessory" />
            <x-accessories.accessory-purchase :accessory="$accessory" />
        </div>

        <div class="row row-eq-height">
            <div class="col-12 col-lg-8 mb-4">
                <x-locations.location-modal :asset="$accessory"/>
            </div>
            <div class="col-12 col-lg-4 mb-4">
                <x-manufacturers.manufacturer-modal :asset="$accessory"/>
            </div>
        </div>

        <div class="row row-eq-height">
            <x-accessories.accessory-log :accessory="$accessory"/>
            <div class="col-12 col-lg-6 mb-4">
                <x-comments.comment-layout :asset="$accessory"/>
            </div>   
        </div>
        
    </section>

@endsection

@section('modals')
    <!-- Status Model Modal-->
    <div class="modal fade bd-example-modal-lg" id="accessoryModalStatus" tabindex="-1" role="dialog"
         aria-labelledby="accessoryModalStatusLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="accessoryModalStatusLabel">Change the Status
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('accessories.status', $accessory->id)}}" method="post">
                <div class="modal-body">
                    @csrf
                    <select name="status" class="form-control">
                        @foreach(\App\Models\Status::all() as $status)
                        <option value="{{ $status->id}}" @if($status->id == $accessory->status_id){{ 'selected'}}@endif>{{ $status->name }}</option>  
                        @endforeach  
                    </select> 
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-green" type="submit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- User Delete Modal-->
    <div class="modal fade bd-example-modal-lg" id="removeLocationModal" tabindex="-1" role="dialog"
         aria-labelledby="removeLocationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeLocationModalLabel">Are you sure you want to send this accessory to the Recycle Bin?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input id="location-id" type="hidden" value="{{ $accessory->id }}">
                    <p>Select "Send to Bin" to send this accessory to the Recycle Bin.</p>
                    <small class="text-danger">**This is not permanent and the accessory can be restored in the accessories Recycle Bin. </small>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-coral" type="button" id="confirmBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- comments Modal-->
    <div class="modal fade bd-example-modal-lg" id="commentModalOpen" tabindex="-1" role="dialog"
         aria-labelledby="commentModalOpen" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalOpenLabel">Creating a New comment for
                        <strong>{{$accessory->name}}</strong></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('accessories.comment', $accessory->id) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>Fill Out the Title Field and Body to continue...</p>
                        <input type="hidden" name="accessory_id" value="{{ $accessory->id }}">
                    </div>
                    <div class="form-group pr-3 pl-3">
                        <label class="font-weight-bold" for="title">Comment Title</label>
                        <input type="text"
                               class="form-control <?php if ($errors->has('title')) {?>border-danger<?php }?>"
                               name="title" id="title" placeholder="Comment Title">
                    </div>
                    <div class="form-group pl-3 pr-3">
                        <label
                            class="font-weight-bold <?php if ($errors->has('comment')) {?>border-danger<?php }?>"
                            for="comment_content">Notes</label>
                        <textarea name="comment" id="comment" class="form-control" rows="5"></textarea>

                    </div>
                    <div class="p-2 text-lg-right">
                        <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-green" type="button" id="commentUpload">
                            Save
                        </button>
                    </div>
                    @csrf
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
                        <strong>{{$accessory->name}}</strong></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="updateForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <p>Fill Out the Title Field and Body to continue...</p>
                        <input type="hidden" name="accessory_id"  value="{{ $accessory->id }}">
                    </div>
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
                        <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-green" type="button" id="commentUpload">
                            Save
                        </button>
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
                    <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-coral" type="button" id="confirmCommentBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script>
        $('.deleteBtn').click(function () {
            $('#location-id').val($(this).data('id'))
            //showModal
            $('#removeLocationModal').modal('show')
        });

        $('#confirmBtn').click(function () {
            var form = '#' + 'form' + $('#location-id').val();
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
