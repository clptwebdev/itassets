@extends('layouts.app')

@section('css')

@endsection

@section('content')
    <form id="form{{$consumable->id}}" action="{{ route('consumables.destroy', $consumable->id) }}" method="POST">
        @csrf
        @method('DELETE')

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">View consumables</h1>
            <div>
                <a href="{{ route('consumables.index')}}"
                   class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                        class="fas fa-chevron-left fa-sm text-white-50"></i> Back</a>
                <a class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm deleteBtn" href="#"
                   data-id="{{$consumable->id}}"><i class=" fas fa-trash fa-sm text-white-50"></i>Delete</a>
                <a href="{{ route('consumables.edit', $consumable->id)}}"
                   class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm"><i
                        class="fas fa-plus fa-sm text-white-50"></i> Edit</a>
                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                        class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
            </div>
        </div>
    </form>

    @if(session('danger_message'))
        <div class="alert alert-danger"> {{ session('danger_message')}} </div>
    @endif

    @if(session('success_message'))
        <div class="alert alert-success"> {{ session('success_message')}} </div>
    @endif
    <section>
        <p class="mb-4">Information regarding {{ $consumable->name }}, the assets that are currently assigned to the
            consumable and any request information.</p>

        <div class="row">

            <div class="col-12 col-sm-8 col-md-9 col-xl-10">
                <div class="card shadow h-100 pb-2">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold">consumable Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row no-gutters">
                            <div class="col mr-2">
                                <div class="mb-1">
                                    <p><strong>consumable Item Name: </strong>{{ $consumable->name }}</p>
                                    <p><strong>Serial Number: </strong>{{ $consumable->serial_no }}</p>
                                    <p><strong>Order Number: </strong>{{ $consumable->order_no }}</p>
                                    <p><strong>consumable Status: </strong>{{ $consumable->status->name ??'N/A'}}</p>
                                    <p><strong>Purchase
                                            Date: </strong> {{\Carbon\Carbon::parse($consumable->purchased_date)->format('Y-m-d')}}
                                    </p>
                                    <p><strong>Purchase Cost: </strong> £{{ $consumable->purchased_cost }}</p>
                                </div>
                            </div>
                            <div class="col-auto">
                                @if ($consumable->photo()->exists())
                                    <img src="{{ $consumable->photo->path ?? 'null' }}"
                                         alt="{{ $consumable->name}}" width="60px">
                                @else
                                    <i class="fas fa-school fa-2x text-gray-300"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-10 p-0">
            <div class="card shadow h-100 mt-3">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">Comments</h6>
                </div>
                <div class="card-body">
                    <div class="row no-gutters">
                        <div class="col mr-2">
                            <div class="mb-1">
                                <div class="row align-items-start">
                                    @foreach($consumable->comment as $comment)
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
    </section>

@endsection

@section('modals')

    <!-- User Delete Modal-->
    <div class="modal fade bd-example-modal-lg" id="removeLocationModal" tabindex="-1" role="dialog"
         aria-labelledby="removeLocationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeLocationModalLabel">Are you sure you want to delete this
                        Consumable?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input id="location-id" type="hidden" value="{{ $consumable->id }}">
                    <p>Select "Delete" to remove this Consumable from the system.</p>
                    <small class="text-danger">**Warning this is permanent. All assets assigned to this location will
                        become available.</small>
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
                        <strong>{{$consumable->name}}</strong></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('consumable.comment') }}" method="POST" enctype="multipart/form-data">
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
        // Create comment , Ignore the ID's

        $('#commentModal').click(function () {
            //showModal
            $('#commentModalOpen').modal('show')
        });

    </script>

@endsection
