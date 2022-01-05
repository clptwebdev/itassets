@extends('layouts.app')

@section('title', 'View '.$location->name)

@section('css')
<link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">View Location</h1>
    <div>
        <a href="{{ route('location.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                class="fas fa-chevron-left fa-sm text-white-50"></i> Back</a>
        @can('delete', $location)
        <a href="#" class="d-none d-sm-inline-block btn btn-sm bg-coral text-white shadow-sm deleteBtn"><i
                class="fas fa-trash fa-sm text-white-50"></i> Delete</a>
        @endcan
        @can('update', $location)
        <a href="{{ route('location.edit', $location->id)}}" class="d-none d-sm-inline-block btn btn-sm bg-yellow text-white shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Edit</a>
        @endcan
        @can('view', $location)
        <a href="{{ route('location.showPdf', $location->id)}}" class="d-none d-sm-inline-block btn btn-sm bg-blue text-white shadow-sm loading"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
        @endcan
    </div>
</div>

@if(session('danger_message'))
<div class="alert alert-danger"> {!! session('danger_message')!!} </div>
@endif

@if(session('success_message'))
<div class="alert alert-success"> {!! session('success_message')!!} </div>
@endif

<section>
    <p class="mb-4">Information regarding {{ $location->name }}, the assets that are currently assigned to the location and any request information.</p>

    <div class="row pl-4 pr-2 mb-4">
        <div class="col-12 col-sm-4 col-md-3 col-xl-2 bg-white rounded overflow-hidden d-flex justify-content-center align-items-center" style="border: solid 3px {{ $location->icon ?? '#666'}};">
            @if($location->photo()->exists())
            <img src="{{ asset($location->photo->path) }}" width="100%" alt="{{ $location->name }}" title="{{ $location->name }}">
            @endif
        </div>
        <div class="col-12 col-sm-8 col-md-9 col-xl-10">
            <div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid {{$location->icon}};">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold" style="color: {{$location->icon}};">Location Information</h6>
                </div>
                <div class="card-body">
                    <div class="row no-gutters">
                        <div class="col mr-2">
                            <div class="mb-1">
                                {{ $location->name }}<br>
                                <p>{{ $location->address_1 }}<br>
                                    @if($location->address_2 != "")
                                    {{ $location->address_2 }}<br>
                                    @endif
                                    {{ $location->city }}<br>
                                    {{ $location->postcode }}</p>
                                <p>Tel: {{ $location->telephone }}</p>
                                <p>Email: {{ $location->email }}</p>
                                <p>* Spent £{{number_format($location->expenditure('2021'), 2, '.', '');}} in the Year 2015</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Asset Informationn --}}

    
    <div class="row mb-4">
        {{-- Expenditure --}}
        <div class="col-12 col-md-6">
            <div class="card shadow h-100">
                <div id="chart" style="height: 300px;"></div>
            </div>
        </div>
        {{-- Donated Information --}}
    </div>

    <div class="row mb-4">
        {{-- Depreciation Information --}}
        <div class="col-12 col-md-6">
            <div class="card shadow h-100">

            </div>
        </div>
        {{-- Audit Information --}}
    </div>


</section>


@endsection

@section('modals')
<!-- User Delete Modal-->
<div class="modal fade bd-example-modal-lg" id="removeModal" tabindex="-1" role="dialog"
    aria-labelledby="removeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('location.destroy', $location->id)}}" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeModalLabel">Are you sure you want to delete this Location?
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    @method('DELETE')
                    <input id="location-id" type="hidden" value="{{ $location->id }}">
                    <p>Select "Delete" to remove this location from the system.</p>
                    <small class="text-danger">**Warning this is permanent. All assets assigned to this location will become
                        available.</small>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-coral" type="submit">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
    $('.deleteBtn').click(function() {
        $('#removeModal').modal('show')
    });

    $(document).ready( function () {
        $('table.logs').DataTable({
                "autoWidth": false,
                "pageLength": 10,
            });
    } );
</script>
 <!-- Charting library -->
 <script src="https://unpkg.com/chart.js@2.9.3/dist/Chart.min.js"></script>
 <!-- Chartisan -->
 <script src="https://unpkg.com/@chartisan/chartjs@^2.1.0/dist/chartisan_chartjs.umd.js"></script>
 <!-- Your application script -->
 <script>
   const chart = new Chartisan({
     el: '#chart',
     url: 'api/chart/exp_chart ',
     // You can also pass the data manually instead of the url:
     // data: { ... }
   })
 </script>

@endsection