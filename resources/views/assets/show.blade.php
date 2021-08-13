@extends('layouts.app')

@section('css')

@endsection

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">View Asset</h1>
        <div>
            <a href="{{ route('assets.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                    class="fas fa-chevron-left fa-sm text-white-50"></i> Back</a>
            @can('generatePDF', $asset)
            <a href="{{ route('asset.showPdf', $asset->id)}}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                        class="fas fa-file-pdf fa-sm text-white-50"></i> Generate Report</button>
            @endcan
            @can('edit', $asset)
            <a href="{{ route('assets.edit', $asset->id)}}"
               class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm"><i
                    class="fas fa-plus fa-sm text-white-50"></i> Edit</a>
            @endcan
            <form class="d-inline-block" id="form{{$asset->id}}" action="{{ route('assets.destroy', $asset->id) }}"
                method="POST">
            @csrf
            @method('DELETE')
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm deleteBtn" data-id="{{$asset->id}}"><i
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
        <x-assets.asset-modal :asset="$asset" />
        <x-assets.asset-purchase :asset="$asset" />
    </div>

    <div class="row row-eq-height ">
        <x-locations.location-modal :asset="$asset"/>
        <x-manufacturers.manufacturer-modal :asset="$asset"/>
        <x-assets.asset-log :asset="$asset"/>
    </div>
    <div class="col-xl-12 p-0">
        <div class="card shadow h-100 mt-3">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold">Comments</h6>
            </div>
            <div class="card-body">
                <div class="row no-gutters">
                    <div class="col mr-2">
                        <div class="mb-1">
                            <div class="row align-items-start">
                                @foreach($asset->comment as $comment)
                                    <x-comments.comment-layout :comment="$comment"/>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button id="commentModal" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm mb-3">
                            Add New
                            Comment
                        </button>
                        <i class="fas fa-comments fa-2x text-gray-300 pt-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('modals')

    <!-- asset Delete Modal-->
    <div class="modal fade bd-example-modal-lg" id="removeassetModal" tabindex="-1" role="dialog"
         aria-labelledby="removeassetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeassetModalLabel">Are you sure you want to delete this item?
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
    <div class="modal fade bd-example-modal-lg" id="commentModalOpen" tabindex="-1" role="dialog"
         aria-labelledby="commentModalOpen" aria-hidden="true">
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
                    <input type="hidden" name="asset_id" value="{{ $asset->id }}">
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
            $('#asset-id').val($(this).data('id'))
            //showModal
            $('#removeassetModal').modal('show')
        });

        $('#confirmBtn').click(function () {
            var form = '#' + 'form' + $('#asset-id').val();
            $(form).submit();
        });

        $(document).ready(function () {
            $('#assetsTable').DataTable({
                "columnDefs": [{
                    "targets": [0, 5],
                    "orderable": false,
                }],
                "order": [[1, "asc"]]
            });
        });
        //comments create show
        $('#commentModal').click(function () {
            //showModal
            $('#commentModalOpen').modal('show')
        });

    </script>

@endsection
