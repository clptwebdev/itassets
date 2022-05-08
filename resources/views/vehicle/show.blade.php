@extends('layouts.app')

@section('title', "View Vehicle")

@section('css')

@endsection

@section('content')
    <x-wrappers.nav title="Show {{$vehicle->name}} in Vehicle">

        @can('viewAll', \App\Models\Vehicle::class)
            <x-buttons.return :route="route('vehicles.index')">Vehicle</x-buttons.return>
        @endcan
        @can('generatePDF', \App\Models\Vehicle::class)
            <x-buttons.reports :route="route('vehicle.showPdf', $vehicle->id)"/>
        @endcan
    </x-wrappers.nav>
    <x-handlers.alerts/>
    <div class="container card">
        <div class="card-body">
            <ul id="tab-bar" class="nav nav-tabs">

                <li class="nav-item">
                    <a class="nav-link active" id="location-tab" data-bs-toggle="tab" href="#location" role="tab"
                       aria-controls="home" aria-selected="true">Vehicle Information</a>
                </li>
            </ul>
            <div class="tab-content border-left border-right border-bottom border-gray" id="myTabContent">
                <div class="tab-content border-left border-right border-bottom border-gray" id="myTabContent">

                    <div class="tab-pane fade show p-2 pt-4 active" id="location" role="tabpanel"
                         aria-labelledby="location-tab">
                        <div class="row">
                            <div class="col-12 col-md-6 p-4 mb-3 ">
                                <h4 class="font-weight-600 mb-4"><strong>Name:</strong><span>{{$vehicle->name}}</span>
                                </h4>
                                <h5 class="font-weight-600 mb-4">
                                    <strong>Registration:</strong><span>{{$vehicle->registration}}</span></h5>
                                <p><strong>Depreciation:</strong> {{$vehicle->depreciation}} Years</p>
                                <p><strong>Date
                                           created:</strong><br>{{\Carbon\Carbon::parse($vehicle->created_at)->format('jS M Y')}}
                                </p>
                                <p><strong>Purchase Cost (At Time of
                                           Purchase):</strong><br>£{{number_format( (float) $vehicle->purchased_cost, 2, '.', ',' )}}
                                </p>
                                <hr>
                                <p><strong>Purchase
                                           date</strong><br>{{\Carbon\Carbon::parse($vehicle->purchased_date)->format('jS M Y')}}
                                </p>
                                <p><strong>
                                        Supplier</strong><br>{{$vehicle->supplier->name}}
                                </p>
                            </div>
                            <div class="col-12 col-md-6 p-4 mb-3 ">
                                <div id="locationInfo" class="bg-light p-4">
                                    <div class="model_title text-center h4 mb-3">{{$vehicle->location->name}}</div>
                                    <div class="model_image p-4 d-flex justify-content-center align-items-middle">
                                        @if($vehicle->location()->exists() && $vehicle->location->photo()->exists())
                                            <img id="profileImage" src="{{ asset($vehicle->location->photo->path) }}"
                                                 height="200px" alt="Select Profile Picture">
                                        @else
                                            <img id="profileImage" src="{{ asset('images/svg/location-image.svg') }}"
                                                 height="200px" alt="Select Profile Picture">
                                        @endif
                                    </div>
                                    <div class="model_no py-2 px-4 text-center">
                                        {{$vehicle->location->full_address(', ')}}
                                    </div>
                                    <div class="model_no py-2 px-4 text-center">
                                        {{$vehicle->location->email}}
                                    </div>
                                    <div class="model_no py-2 px-4 text-center">
                                        {{ $vehicle->location->telephone}}
                                    </div>
                                    <div class="model_no py-2 px-4 text-center">
                                        {{ $vehicle->location->notes}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-12 my-4">
                    <x-comments.comment-layout :asset="$vehicle"/>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('modals')
    <x-modals.delete/>
    <x-modals.add-comment :route="route('vehicle.comment', $vehicle->id)" :model="$vehicle" title="vehicle"/>
    <x-modals.edit-comment :model="$vehicle"/>
    <x-modals.delete-comment/>
@endsection

@section('js')
    <script src="{{asset('js/delete.js')}}"></script>
    <script src="{{asset('js/comment.js')}}"></script>
@endsection
