@extends('layouts.app')

@section('title', "View Consumable")

@section('css')
<link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">View Consumables</h1>
        <div>
            <a href="{{ route('consumables.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                    class="fas fa-chevron-left fa-sm text-white-50"></i> Back</a>
            @can('generatePDF', $consumable)
            <a href="{{ route('consumables.showPdf', $consumable->id)}}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm"><i
                        class="fas fa-file-pdf fa-sm text-white-50"></i> Generate Report</a>
            @endcan
            @can('edit', $consumable)
            <a href="{{ route('consumables.edit', $consumable->id)}}"
               class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm"><i
                    class="fas fa-edit fa-sm text-white-50"></i> Edit</a>
            @endcan
            <form class="d-inline-block id="form{{$consumable->id}}" action="{{ route('consumables.destroy', $consumable->id) }}"
                method="POST">
            @csrf
            @method('DELETE')
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm deleteBtn" data-id="{{$consumable->id}}"><i
                    class="fas fa-trash fa-sm text-white-50"></i> Delete</a>
            </form>
        </div>
    </div>

    @if(session('danger_message'))
        <div class="alert alert-danger"> {{ session('danger_message')}} </div>
    @endif

    @if(session('success_message'))
        <div class="alert alert-success"> {{ session('success_message')}} </div>
    @endif

    <div class="row row-eq-height">
        <x-consumables.consumable-info :consumable="$consumable" />
        <x-consumables.consumable-purchase :consumable="$consumable" />
    </div>

    <div class="row row-eq-height">
        <div class="col-12 col-lg-8 mb-4">
            <x-locations.location-modal :asset="$consumable"/>
        </div>
        
        <div class="col-12 col-lg-4 mb-4">
            <x-manufacturers.manufacturer-modal :asset="$consumable"/>
        </div>
        
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
    <div class="modal fade bd-example-modal-lg" id="consumableStatus" tabindex="-1" role="dialog"
         aria-labelledby="consumableStatusLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="consumableStatusLabel">Are you sure you want to delete this item?
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
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-success" type="submit">Update</button>
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
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" type="button" id="confirmBtn">Send to Bin</button>
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
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" type="button" id="commentUpload">
                            Save
                        </button>
                    </div>
                    @csrf
                </form>
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
            

    </script>

@endsection
