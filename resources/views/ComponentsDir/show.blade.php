@extends('layouts.app')

@section('title', 'View Component')

@section('css')
<link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')
    <form id="form{{$component->id}}" action="{{ route('components.destroy', $component->id) }}" method="POST">
        @csrf
        @method('DELETE')

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">View Component</h1>
            <div>
                <a href="{{ route('components.index')}}"
                   class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                        class="fas fa-chevron-left fa-sm text-white-50"></i> Back</a>
                @can('delete', $component)
                <a class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm deleteBtn" href="#"
                   data-id="{{$component->id}}"><i class=" fas fa-trash fa-sm text-white-50"></i>Delete</a>
                @endcan
                @can('edit', $component)
                <a href="{{ route('components.edit', $component->id)}}"
                   class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm"><i
                        class="fas fa-edit fa-sm text-white-50"></i> Edit</a>
                @endcan
                @can('export', $component)
                <a href="{{ route('components.showPdf', $component->id)}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                        class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                @endcan
            </div>
        </div>
    </form>

    @if(session('danger_message'))
        <div class="alert alert-danger"> {{ session('danger_message')}} </div>
    @endif

    @if(session('success_message'))
        <div class="alert alert-success"> {{ session('success_message')}} </div>
    @endif
    <section class="m-auto">
        <p class="mb-4">Information regarding {{ $component->name }} including the location and any comments made by staff. </p>

        <div class="row row-eq-height">
            <x-components.component-info :component="$component" />
            <x-components.component-purchase :component="$component" />
        </div>

        <div class="row row-eq-height">
            <div class="col-12 col-lg-8 mb-4">
                <x-locations.location-modal :asset="$component"/>
            </div>
            <div class="col-12 col-lg-4 mb-4">
                <x-manufacturers.manufacturer-modal :asset="$component"/>
            </div>
        </div>

        <div class="row row-eq-height">
            <x-components.component-log :component="$component"/>
            <div class="col-12 col-lg-6 mb-4">
                <x-comments.comment-layout :asset="$component"/>
            </div>   
        </div>
        
    </section>

@endsection

@section('modals')

    <!-- User Delete Modal-->
    <div class="modal fade bd-example-modal-lg" id="removeLocationModal" tabindex="-1" role="dialog"
         aria-labelledby="removeLocationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeLocationModalLabel">Are you sure you want to send this Component to the Recycle Bin?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input id="location-id" type="hidden" value="{{ $component->id }}">
                    <p>Select "Send to Bin" to send this Component to the Recycle Bin.</p>
                    <small class="text-danger">**This is not permanent and the component can be restored in the Components Recycle Bin. </small>
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
                    <h5 class="modal-title" id="commentModalOpenLabel">Creating a New comment for
                        <strong>{{$component->name}}</strong></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('component.comment', $component->id) }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>Fill Out the Title Field and Body to continue...</p>
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
