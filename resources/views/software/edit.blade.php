@extends('layouts.app')

@section('title', 'Edit ' . $software->name . ' Software')

@section('css')

@endsection

@section('content')
    <x-form.layout :action="route('softwares.update' , $software->id)" method="PUT">
        <x-wrappers.nav title="Add Software">
            <x-buttons.return :route="route('softwares.index')">Software</x-buttons.return>
            <x-buttons.submit>Save</x-buttons.submit>
        </x-wrappers.nav>
        <section>
            <p class="mb-4">
                Edit Software in the Apollo Asset Manager. </p>
            <div class="row row-eq-height no-gutters p-0 p-md-4 container m-auto">
                <div class="col-12">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <x-form.errors/>
                            <x-handlers.alerts/>
                            <ul id="tab-bar" class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" id="location-tab" data-bs-toggle="tab" href="#location"
                                       role="tab" aria-controls="home" aria-selected="true">Software Information
                                                                                            for {{$software->name}}</a>
                                </li>
                            </ul>
                            <div class="tab-content border-left border-right border-bottom border-gray"
                                 id="myTabContent">

                                <div class="tab-pane fade show p-2 pt-4 active" id="location" role="tabpanel"
                                     aria-labelledby="location-tab">
                                    <div class="row">
                                        <div class="col-12 col-md-6 p-4 mb-3 ">
                                            <div class="form-group">
                                                <x-form.input name="name" formAttributes="required"
                                                              :value="$software->name"/>
                                            </div>
                                            <div class='form-group'>
                                                <x-form.select name="supplier_id" :models="$suppliers"
                                                               formAttributes="required"
                                                               selected="{{$software->supplier_id}}"/>
                                            </div>
                                            <div class="form-group position-relative">
                                                <label for="findLocation">Location</label>
                                                <input type="hidden" id="location_id" name="location_id"
                                                       class="form-control mb-3" readonly
                                                       value="{{$software->location_id}}">
                                                <input class="form-control" type="text" name="find_location"
                                                       id="findLocation" value="{{$software->location->name}}"
                                                       placeholder="Search for Location" autocomplete="off">
                                                <div id="locationResults"
                                                     class="w-100 h-auto mb-5 d-block search-modal position-absolute"
                                                     style="visibility: hidden; z-index: 2;">
                                                    <ul id="locationSelect">
                                                        <li>Nothing to Return</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <x-form.input name="purchased_cost" formAttributes="required"
                                                              :value="$software->purchased_cost"/>
                                            </div>

                                            <div class="form-group">
                                                <x-form.date name="purchased_date" formAttributes="required"
                                                             :value="\Carbon\Carbon::parse($software->purchased_date)->format('Y-m-d')"/>
                                            </div>
                                            <div class="form-group">
                                                <x-form.input name="depreciation" formAttributes="required"
                                                              :value="$software->depreciation"/>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 p-4 mb-3 ">
                                            <div id="locationInfo" class="bg-light p-4">
                                                <div
                                                    class="model_title text-center h4 mb-3">{{$software->location->name}}</div>
                                                <div
                                                    class="model_image p-4 d-flex justify-content-center align-items-middle">
                                                    @if($software->location()->exists() && $software->location->photo()->exists())
                                                        <img id="profileImage"
                                                             src="{{ asset($software->location->photo->path) }}"
                                                             height="200px" alt="Select Profile Picture">
                                                    @else
                                                        <img id="profileImage"
                                                             src="{{ asset('images/svg/location-image.svg') }}"
                                                             height="200px" alt="Select Profile Picture">
                                                    @endif
                                                </div>
                                                <div class="model_no py-2 px-4 text-center">
                                                    {{$software->location->full_address(', ')}}
                                                </div>
                                                <div class="model_no py-2 px-4 text-center">
                                                    {{$software->location->telephone}}
                                                </div>
                                                <div class="model_no py-2 px-4 text-center">
                                                    {{$software->location->email}}
                                                </div>
                                                <div class="model_no py-2 px-4 text-center">
                                                    {{$software->location->notes}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </x-form.layout>

@endsection

@section('modals')

@endsection

@section('js')
    {{-- Require the Page to have the #FindLocation and the #LocationResults elements on the page --}}
    <script src="{{asset('js/location-preview.js')}}"></script>
@endsection
