@extends('layouts.app')

@section('title', "View Consumable")

@section('css')
<link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">View Consumables</h1>
        <div>
            <a href="{{ route('consumables.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                    class="fas fa-chevron-left fa-sm text-white-50"></i> Back</a>
            @can('generatePDF', $consumable)
            <a href="{{ route('consumables.showPdf', $consumable->id)}}" class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm loading"><i
                        class="fas fa-file-pdf fa-sm text-white-50"></i> Generate Report</a>
            @endcan
            @can('update', $consumable)
            <a href="{{ route('consumables.edit', $consumable->id)}}"
               class="d-none d-sm-inline-block btn btn-sm btn-yellow shadow-sm"><i
                    class="fas fa-edit fa-sm text-white-50"></i> Edit</a>
            @endcan
            <form class="d-inline-block id="form{{$consumable->id}}" action="{{ route('consumables.destroy', $consumable->id) }}"
                method="POST">
            @csrf
            @method('DELETE')
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-coral shadow-sm deleteBtn" data-id="{{$consumable->id}}"><i
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
        <x-consumables.consumable-info :consumable="$consumable" />
        <x-consumables.consumable-purchase :consumable="$consumable" />
    </div>

    <div class="row row-eq-height">
        @if($consumable->location()->exists())
        <div class="col-12 col-lg-8 mb-4">
            <x-locations.location-modal :asset="$consumable"/>
        </div>
        @endif
        @if($consumable->manufacturer()->exists())
        <div class="col-12 col-lg-4 mb-4">
            <x-manufacturers.manufacturer-modal :asset="$consumable"/>
        </div>
        @endif
    </div>
    <div class="row row-eq-height">
        <x-consumables.consumable-log :consumable="$consumable"/>
        <div class="col-12 col-lg-6 mb-4">
            <x-comments.comment-layout :asset="$consumable"/>
        </div>   
    </div>
    

@endsection

@section('modals')
    <!-- consumable Status Model Modal-->
    <div class="modal fade bd-example-modal-lg" id="consumableModalStatus" tabindex="-1" role="dialog"
         aria-labelledby="consumableModalStatusLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="consumableModalStatusLabel">Change Item Status
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('consumables.status', $consumable->id)}}" method="post">
                <div class="modal-body">
                    @csrf
                    <select name="status" class="form-control">
                        @foreach(\App\Models\Status::all() as $status)
                        <option value="{{ $status->id}}" @if($status->id == $consumable->status_id){{ 'selected'}}@endif>{{ $status->name }}</option>  
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
    <!-- consumable Delete Modal-->
    <div class="modal fade bd-example-modal-lg" id="removeconsumableModal" tabindex="-1" role="dialog"
         aria-labelledby="removeconsumableModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeconsumableModalLabel">Are you sure you want to send this Consumable to the Recycle Bin?
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input id="consumable-id" type="hidden" value="">
                    <p>Select "Send to Bin" to send this item to the Recycle Bin.</p>
                    <small class="text-danger">**Warning this is not permanent. This Consumable can be restored inside the Recycle Bin.</small>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-coral" type="button" id="confirmBtn">Send to Bin</button>
                </div>
            </div>
        </div>
    </div>
    <!-- comments Modal-->
    <div class="modal fade bd-example-modal-lg" id="newCommentModal" tabindex="-1" role="dialog"aria-labelledby="commentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalLabel">Creating a New comment for
                        <strong>{{$consumable->name}}</strong></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('consumables.comment') }}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="consumable_id" value="{{ $consumable->id }}">
                    <div class="modal-body">
                        <p>Fill Out the title Field and Body to continue...</p>
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
                            for="comment">Notes</label>
                        <textarea name="comment" id="content" class="form-control" rows="5"></textarea>

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
                        <strong>{{$consumable->name}}</strong></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="updateForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <p>Fill Out the Title Field and Body to continue...</p>
                        <input type="hidden" name="accessory_id"  value="{{ $consumable->id }}">
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
            $('#consumable-id').val($(this).data('id'))
            //showModal
            $('#removeconsumableModal').modal('show')
        });

        $('#confirmBtn').click(function () {
            var form = '#' + 'form' + $('#consumable-id').val();
            $(form).submit();
        });

        $('#commentModal').click(function () {
            //showModal
            $('#newCommentModal').modal('show')
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
