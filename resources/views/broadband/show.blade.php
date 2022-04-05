@extends('layouts.app')

@section('title', "View Broadband")

@section('css')

@endsection

@section('content')
    <x-wrappers.nav title="Show {{$broadband->name}} in Broadband">

        @can('viewAll', \App\Models\Broadband::class)
            <x-buttons.return :route="route('broadbands.index')">Broadband</x-buttons.return>
        @endcan
        @can('generatePDF', \App\Models\Broadband::class)
            <x-buttons.reports :route="route('broadband.showPdf', $broadband->id)"/>
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
                                <h4 class="font-weight-600 mb-4">{{$broadband->name ?? 'No Name'}}</h4>
                                <p><strong>Renewal
                                           Date:</strong><br> {{\Illuminate\Support\Carbon::parse($broadband->renewal_date)->format('d-M-Y')}}
                                    <br><small
                                        class='text-gray-600'>{{\Illuminate\Support\Carbon::parse($broadband->renewal_date)->diffForHumans()}}</small>
                                </p>
                                <p><strong>Date
                                           created:</strong><br>{{\Carbon\Carbon::parse($broadband->created_at)->format('jS M Y')}}
                                </p>
                                <p><strong>Purchase Cost (At Time of
                                           Purchase):</strong><br>£{{number_format( (float) $broadband->purchased_cost, 2, '.', ',' )}}
                                </p>
                                <hr>
                                <p><strong>Purchase
                                           date</strong><br>{{\Carbon\Carbon::parse($broadband->purchased_date)->format('jS M Y')}}
                                </p>
                                <p><strong>Package</strong><br>{{$broadband->package}}
                                </p>
                                <p><strong>
                                        Supplier</strong><br>{{$broadband->supplier->name ?? 'N/A'}}
                                </p>
                                <p class='font-weight-bold'>Broadband Status:</p>
                                @if($broadband->isExpired())
                                    <div class='alert alert-danger mx-auto'>
                                        <p class='text-dark'>{{$broadband->name .'`s Subscription, ' ?? $broadband->supplier->name .'`s Subscription, ' . $broadband->package . 'Package'}}
                                            Has Expired , <small
                                                class='text-gray-600'>{{\Illuminate\Support\Carbon::parse($broadband->renewal_date)->diffForHumans()}}</small>
                                        </p>
                                    </div>
                                @else
                                    <div class='alert alert-success mx-auto'>
                                        <p class='text-dark'>{{$broadband->name .'`s Subscription, ' ?? $broadband->supplier->name .'`s Subscription, ' . $broadband->package . 'Package'}}
                                            is valid for another , <small
                                                class='text-gray-600'>{{\Illuminate\Support\Carbon::parse($broadband->renewal_date)->diffForHumans()}}</small>
                                        </p>
                                    </div>
                                @endif

                            </div>
                            <div class="col-12 col-md-6 p-4 mb-3 ">
                                <div id="locationInfo" class="bg-light p-4">
                                    <div class="model_title text-center h4 mb-3">{{$broadband->location->name}}</div>
                                    <div class="model_image p-4 d-flex justify-content-center align-items-middle">
                                        @if($broadband->location()->exists() && $broadband->location->photo()->exists())
                                            <img id="profileImage" src="{{ asset($broadband->location->photo->path) }}"
                                                 height="200px" alt="Select Profile Picture">
                                        @else
                                            <img id="profileImage" src="{{ asset('images/svg/location-image.svg') }}"
                                                 height="200px" alt="Select Profile Picture">
                                        @endif
                                    </div>
                                    <div class="model_no py-2 px-4 text-center">
                                        {{$broadband->location->full_address(', ')}}
                                    </div>
                                    <div class="model_no py-2 px-4 text-center">
                                        {{$broadband->location->email}}
                                    </div>
                                    <div class="model_no py-2 px-4 text-center">
                                        {{ $broadband->location->telephone}}
                                    </div>
                                    <div class="model_no py-2 px-4 text-center">
                                        {{ $broadband->location->notes}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-12 my-4">
                    <x-comments.comment-layout :asset="$broadband"/>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('modals')
    <x-modals.delete/>
    <x-modals.add-comment :route="route('broadband.comment', $broadband->id)" :model="$broadband" title="broadband"/>
    <x-modals.edit-comment :model="$broadband"/>
    <x-modals.delete-comment/>
@endsection

@section('js')
    <script src="{{asset('js/delete.js')}}"></script>
    <script src="{{asset('js/comment.js')}}"></script>
@endsection
