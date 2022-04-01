@extends('layouts.app')

@section('title', "View License")

@section('css')

@endsection

@section('content')
    <x-wrappers.nav title="Show {{$license->name}} in License">

        @can('viewAll', \App\Models\License::class)
            <x-buttons.return :route="route('licenses.index')">License</x-buttons.return>
        @endcan
        @can('generatePDF',\App\Models\License::class)
            <x-buttons.reports :route="route('license.showPdf', $license->id)"/>
        @endcan
        @can('update', $license)
            <x-buttons.edit :route="route('licenses.edit', $license->id)"/>
        @endcan
    </x-wrappers.nav>
    <x-handlers.alerts/>
    <div class="container card">
        <div class="card-body">
            <ul id="tab-bar" class="nav nav-tabs">

                <li class="nav-item">
                    <a class="nav-link active" id="location-tab" data-bs-toggle="tab" href="#location" role="tab"
                       aria-controls="home" aria-selected="true">Broadband Information</a>
                </li>
            </ul>
            <div class="tab-content border-left border-right border-bottom border-gray" id="myTabContent">
                <div class="tab-content border-left border-right border-bottom border-gray" id="myTabContent">

                    <div class="tab-pane fade show p-2 pt-4 active" id="location" role="tabpanel"
                         aria-labelledby="location-tab">
                        <div class="row">
                            <div class="col-12 col-md-6 p-4 mb-3 ">
                                <h4 class="font-weight-600 mb-4">{{$license->name ?? 'No Name'}}</h4>
                                <p>
                                    <strong>Expiry:</strong><br> {{\Illuminate\Support\Carbon::parse($license->expiry)->format('d-M-Y')}}
                                    <br><small
                                        class='text-gray-600'>{{\Illuminate\Support\Carbon::parse($license->renewal_date)->diffForHumans()}}</small>
                                </p>
                                <p><strong>Date
                                           created:</strong><br>{{\Carbon\Carbon::parse($license->created_at)->format('jS M Y')}}
                                </p>
                                <p><strong>Purchase Cost (At Time of
                                           Purchase):</strong><br>£{{number_format( (float) $license->purchased_cost, 2, '.', ',' )}}
                                </p>
                                <hr>
                                <p><strong>Contact:</strong><br>{{$license->contact ?? 'No Contact Email'}}
                                </p>
                                <p><strong>
                                        Supplier:</strong><br>{{$license->supplier->name ?? 'No Supplier'}}
                                </p>
                                <p class='font-weight-bold'>License Status:</p>
                                @if($license->isExpired())
                                    <div class='alert alert-danger mx-auto'>
                                        <p class='text-dark'>{{$license->name .'`s Subscription,'}}
                                            Has Expired , <small
                                                class='text-gray-600'>{{\Illuminate\Support\Carbon::parse($license->expiry)->diffForHumans()}}</small>
                                        </p>
                                    </div>
                                @else
                                    <div class='alert alert-success mx-auto'>
                                        <p class='text-dark'>{{$license->name .'`s Subscription, '}}
                                            is valid for another , <small
                                                class='text-gray-600'>{{\Illuminate\Support\Carbon::parse($license->expiry)->diffForHumans()}}</small>
                                        </p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-12 col-md-6 p-4 mb-3 ">
                                <div id="locationInfo" class="bg-light p-4">
                                    <div class="model_title text-center h4 mb-3">{{$license->location->name}}</div>
                                    <div class="model_image p-4 d-flex justify-content-center align-items-middle">
                                        @if($license->location()->exists() && $license->location->photo()->exists())
                                            <img id="profileImage" src="{{ asset($license->location->photo->path) }}"
                                                 height="200px" alt="Select Profile Picture">
                                        @else
                                            <img id="profileImage" src="{{ asset('images/svg/location-image.svg') }}"
                                                 height="200px" alt="Select Profile Picture">
                                        @endif
                                    </div>
                                    <div class="model_no py-2 px-4 text-center">
                                        {{$license->location->full_address(', ')}}
                                    </div>
                                    <div class="model_no py-2 px-4 text-center">
                                        {{$license->location->email}}
                                    </div>
                                    <div class="model_no py-2 px-4 text-center">
                                        {{ $license->location->telephone}}
                                    </div>
                                    <div class="model_no py-2 px-4 text-center">
                                        {{ $license->location->notes}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-12 my-4">
                    <x-comments.comment-layout :asset="$license"/>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('modals')
    <x-modals.delete/>
    <x-modals.add-comment :route="route('license.comment', $license->id)" :model="$license" title="license"/>
    <x-modals.edit-comment :model="$license"/>
    <x-modals.delete-comment/>
@endsection

@section('js')
    <script src="{{asset('js/delete.js')}}"></script>
    <script src="{{asset('js/comment.js')}}"></script>
@endsection
