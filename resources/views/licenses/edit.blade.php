@extends('layouts.app')

@section('title', 'Add License')

@section('css')

@endsection

@section('content')
    <x-form.layout :action="route('licenses.store')">
        <x-wrappers.nav title="Add License">
            <x-buttons.return :route="route('licenses.index')">Licenses</x-buttons.return>
            <x-buttons.submit>Save</x-buttons.submit>
        </x-wrappers.nav>
        <section>
            <p class="mb-4">
                Add Licenses to the Apollo Asset Manager. </p>
            <div class="row row-eq-height no-gutters p-0 p-md-4 container m-auto">
                <div class="col-12">
                    <div class="card shadow h-100">
                        <div class="card-body">

                            <x-handlers.alerts/>
                            <ul id="tab-bar" class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" id="location-tab" data-bs-toggle="tab" href="#location"
                                       role="tab" aria-controls="home" aria-selected="true">License Information</a>
                                </li>
                            </ul>
                            <div class="tab-content border-left border-right border-bottom border-gray"
                                 id="myTabContent">

                                <div class="tab-pane fade show p-2 pt-4 active" id="location" role="tabpanel"
                                     aria-labelledby="location-tab">
                                    <div class="row">
                                        <div class="col-12 col-md-6 p-4 mb-3 ">
                                            <div class="form-group">
                                                <x-form.input name="name" :value="$license->name"/>
                                            </div>
                                            <div class='form-group'>
                                                <x-form.select name="supplier_id" :selected="$license->supplier_id"
                                                               :models="$suppliers" formAttributes="required"/>
                                            </div>
                                            <div class="form-group position-relative">
                                                <label for="findLocation">Location</label>
                                                <input type="hidden" id="location_id" name="location_id"
                                                       class="form-control mb-3" readonly
                                                       value="{{$license->location_id}}">
                                                <input class="form-control" type="text" name="find_location"
                                                       id="findLocation" value="{{$license->location->name}}"
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
                                                <x-form.input name="contact" formAttributes="required"
                                                              :value="$license->contact"/>
                                            </div>
                                            <div class="form-group">
                                                <x-form.input name="purchased_cost" :value="$license->purchased_cost"
                                                              formAttributes="required"/>
                                            </div>
                                            <div class="form-group">
                                                <x-form.date name="expiry_date"
                                                             :value="\Carbon\Carbon::parse($license->expiry_date)->format('Y-m-d')"
                                                             formAttributes="required"/>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 p-4 mb-3 ">
                                            <div id="locationInfo" class="bg-light p-4">
                                                <div
                                                    class="model_title text-center h4 mb-3">{{$license->location->name}}</div>
                                                <div
                                                    class="model_image p-4 d-flex justify-content-center align-items-middle">
                                                    @if($license->location()->exists() && $license->location->photo()->exists())
                                                        <img id="profileImage" onclick='getPhotoPage(1)'
                                                             src="{{ asset($license->location->photo->path) }}"
                                                             height="200px" alt="Select Profile Picture">
                                                    @else
                                                        <img id="profileImage" onclick='getPhotoPage(1)'
                                                             src="{{ asset('images/svg/location-image.svg') }}"
                                                             height="200px" alt="Select Profile Picture">
                                                    @endif
                                                </div>
                                                <div class="model_no py-2 px-4 text-center">
                                                    {{$license->location->full_address(', ')}}
                                                </div>
                                                <div class="model_no py-2 px-4 text-center">
                                                    {{$license->location->telephone}}
                                                </div>
                                                <div class="model_no py-2 px-4 text-center">
                                                    {{$license->location->email}}
                                                </div>
                                                <div class="model_no py-2 px-4 text-center">
                                                    {{$license->location->notes}}
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
