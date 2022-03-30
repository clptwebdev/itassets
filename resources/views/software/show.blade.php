@extends('layouts.app')

@section('title', "View Software")

@section('css')

@endsection

@section('content')
    <x-wrappers.nav title="Show {{$software->name}} in Software">

        @can('viewAll', \App\Models\Software::class)
            <x-buttons.return :route="route('softwares.index')">Software</x-buttons.return>
        @endcan
        @can('generatePDF', \App\Models\Software::class)
            <x-buttons.reports :route="route('software.showPdf', $software->id)"/>
        @endcan
    </x-wrappers.nav>
    <x-handlers.alerts/>
    <div class="container card">
        <div class="card-body">
            <ul id="tab-bar" class="nav nav-tabs">

                <li class="nav-item">
                    <a class="nav-link active" id="location-tab" data-bs-toggle="tab" href="#location" role="tab"
                       aria-controls="home" aria-selected="true">Software Information</a>
                </li>
            </ul>
            <div class="tab-content border-left border-right border-bottom border-gray" id="myTabContent">
                <div class="tab-content border-left border-right border-bottom border-gray" id="myTabContent">

                    <div class="tab-pane fade show p-2 pt-4 active" id="location" role="tabpanel"
                         aria-labelledby="location-tab">
                        <div class="row">
                            <div class="col-12 col-md-6 p-4 mb-3 ">
                                <h4 class="font-weight-600 mb-4">{{$software->name}}</h4>
                                <p><strong>Depreciation:</strong> {{$software->depreciation}} Years</p>
                                <p><strong>Date
                                           created:</strong><br>{{\Carbon\Carbon::parse($software->created_at)->format('jS M Y')}}
                                </p>
                                <p><strong>Purchase Cost (At Time of
                                           Purchase):</strong><br>£{{number_format( (float) $software->purchased_cost, 2, '.', ',' )}}
                                </p>
                                <hr>
                                <p><strong>Purchase
                                           date</strong><br>{{\Carbon\Carbon::parse($software->purchased_date)->format('jS M Y')}}
                                </p>
                                <p><strong>
                                        Supplier</strong><br>{{$software->supplier->name}}
                                </p>
                            </div>
                            <div class="col-12 col-md-6 p-4 mb-3 ">
                                <div id="locationInfo" class="bg-light p-4">
                                    <div class="model_title text-center h4 mb-3">{{$software->location->name}}</div>
                                    <div class="model_image p-4 d-flex justify-content-center align-items-middle">
                                        @if($software->location()->exists() && $software->location->photo()->exists())
                                            <img id="profileImage" src="{{ asset($software->location->photo->path) }}"
                                                 height="200px" alt="Select Profile Picture">
                                        @else
                                            <img id="profileImage" src="{{ asset('images/svg/location-image.svg') }}"
                                                 height="200px" alt="Select Profile Picture">
                                        @endif
                                    </div>
                                    <div class="model_no py-2 px-4 text-center">
                                        {{$software->location->full_address(', ')}}
                                    </div>
                                    <div class="model_no py-2 px-4 text-center">
                                        {{$software->location->email}}
                                    </div>
                                    <div class="model_no py-2 px-4 text-center">
                                        {{ $software->location->telephone}}
                                    </div>
                                    <div class="model_no py-2 px-4 text-center">
                                        {{ $software->location->notes}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-12 my-4">
                    <x-comments.comment-layout :asset="$software"/>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('modals')
    <x-modals.delete/>
    <x-modals.add-comment :route="route('software.comment', $software->id)" :model="$software" title="software"/>
    <x-modals.edit-comment :model="$software"/>
    <x-modals.delete-comment/>
@endsection

@section('js')
    <script src="{{asset('js/delete.js')}}"></script>
    <script src="{{asset('js/comment.js')}}"></script>
@endsection
