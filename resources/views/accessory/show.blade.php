@extends('layouts.app')

@section('css')

@endsection

@section('content')
    <form id="form{{$accessory->id}}" action="{{ route('accessories.destroy', $accessory->id) }}" method="POST">
        @csrf
        @method('DELETE')

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">View Accessories</h1>
            <div>
                <a href="{{ route('accessories.index')}}"
                   class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                        class="fas fa-chevron-left fa-sm text-white-50"></i> Back</a>
                <a class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm deleteBtn" href="#"
                   data-id="{{$accessory->id}}"><i class=" fas fa-trash fa-sm text-white-50"></i>Delete</a>
                <a href="{{ route('accessories.edit', $accessory->id)}}"
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
        <p class="mb-4">Information regarding {{ $accessory->name }}, the assets that are currently assigned to the accessory and any request information.</p>

        <div class="row">

            <div class="col-12 col-sm-8 col-md-9 col-xl-10">
                <div class="card shadow h-100 pb-2" >
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold">accessory Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row no-gutters">
                            <div class="col mr-2">
                                <div class="mb-1">
                                    <p><strong>accessory Item Name: </strong>{{ $accessory->name }}</p>
                                    <p><strong>Serial Number: </strong>{{ $accessory->serial_no }}</p>
                                    <p><strong>Order Number: </strong>{{ $accessory->order_no }}</p>
                                    <p><strong>accessory Status: </strong>{{ $accessory->status->name ??'N/A'}}</p>
                                    <p><strong>Purchase Date: </strong> {{\Carbon\Carbon::parse($accessory->purchased_date)->format('Y-m-d')}}</p>
                                    <p><strong>Purchase Cost: </strong> £{{ $accessory->purchased_cost }}</p>
                                </div>
                            </div>
                            <div class="col-auto">
                                @if ($accessory->photo()->exists())
                                    <img src="{{ $accessory->photo->path ?? 'null' }}"
                                         alt="{{ $accessory->name}}" width="60px">
                                @else
                                    <i class="fas fa-school fa-2x text-gray-300"></i>
                                @endif
                            </div>
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
                        Accessory?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input id="location-id" type="hidden" value="{{ $accessory->id }}">
                    <p>Select "Delete" to remove this Accessory from the system.</p>
                    <small class="text-danger">**Warning this is permanent. All assets assigned to this accessory will
                        become available.</small>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" type="button" id="confirmBtn">Delete</button>
                </div>
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

    </script>

@endsection
