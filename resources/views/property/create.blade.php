@extends('layouts.app')

@section('title', 'Create New Asset')

@section('css')

@endsection

@section('content')
    <x-form.layout :action="route('property.store')" >
        <x-wrappers.nav title="Add New Property" >
            <x-buttons.return :route="route('assets.index')" > Assets</x-buttons.return >
            <a href="{{ route('documentation.index')."#collapseThreeAssets"}}"
               class="btn btn-sm  bg-yellow shadow-sm p-2 p-md-1" ><i
                    class="fas fa-question fa-sm text-dark-50 mr-lg-1" ></i ><span class="d-none d-lg-inline-block">Help</span></a >
            <x-buttons.submit >Save</x-buttons.submit >
        </x-wrappers.nav >
        <section >
            <p class="mb-4" >
                Add a new property to the Apollo Asset Manager. 
            </p >
            <div class="row row-eq-height no-gutters p-0 p-md-4 container m-auto" >
                <div class="col-12" >
                    <div class="card shadow h-100" >
                        <div class="card-body" >
                            <x-form.errors />
                            <x-handlers.alerts />


                            <ul id="tab-bar" class="nav nav-tabs" >

                                <li class="nav-item" >
                                    <a class="nav-link active" id="location-tab" data-toggle="tab" href="#location" role="tab"
                                       aria-controls="home" aria-selected="true" >Location Information</a >
                                </li >
                            </ul >
                            <div class="tab-content border-left border-right border-bottom border-gray"
                                 id="myTabContent" >
                                
                                <div class="tab-pane fade show p-2 pt-4 active" id="location" role="tabpanel"
                                     aria-labelledby="location-tab" >
                                    <div class="row" >
                                        <div class="col-12 col-md-6 p-4 mb-3 " >
                                            <div class="form-group" >
                                                <x-form.input name="name" formAttributes="required" />
                                            </div >
                                            <div class="form-group position-relative" >
                                                <label for="findLocation" >Location</label >
                                                <input type="hidden" id="location_id" name="location_id"
                                                       class="form-control mb-3" readonly value="1">
                                                <input class="form-control" type="text" name="find_location"
                                                       id="findLocation" value="" placeholder="Search for Location" autocomplete="off">
                                                <div id="locationResults"
                                                     class="w-100 h-auto mb-5 d-block search-modal position-absolute"
                                                     style="visibility: hidden; z-index: 2;" >
                                                    <ul id="locationSelect" >
                                                        <li >Nothing to Return</li >
                                                    </ul >
                                                </div >
                                            </div >

                                            <div class="form-group" >
                                                <x-form.input name="value" formAttributes="required" />
                                            </div >

                                            <div class="form-group" >
                                                <x-form.date name="date" formAttributes="required" />
                                            </div >

                                            <div class="form-group" >
                                                <x-form.input name="depreciation" formAttributes="required" />
                                            </div >

                                            <div class="form-group">
                                                <label for="name">Property Type</label>
                                                <select id="type" name="type" class="form-control">
                                                    <option value="1">Freehold Land</option>
                                                    <option value="2">Freehold Buildings</option>
                                                    <option value="3">Leasehold Land</option>
                                                    <option value="4">Leasehold Building</option>
                                                </select>
                                            </div>
                                        </div >
                                        <div class="col-12 col-md-6 p-4 mb-3 " >
                                            <div id="locationInfo" class="bg-light p-4" >
                                                <div class="model_title text-center h4 mb-3" >Location Name</div >
                                                <div
                                                    class="model_image p-4 d-flex justify-content-center align-items-middle" >
                                                    <img id="profileImage"
                                                         src="{{ asset('images/svg/location-image.svg') }}"
                                                         height="200px"
                                                         alt="Select Profile Picture" >
                                                </div >
                                                <div class="model_no py-2 px-4 text-center" >
                                                    Address
                                                </div >
                                                <div class="model_no py-2 px-4 text-center" >
                                                    Website
                                                </div >
                                                <div class="model_no py-2 px-4 text-center" >
                                                    Email
                                                </div >
                                                <div class="model_no py-2 px-4 text-center" >
                                                    Notes
                                                </div >
                                            </div >
                                        </div >
                                    </div >
                                </div >

                            </div >
                        </div >
                    </div >
                </div >
            </div >
        </section >
    </x-form.layout >

@endsection

@section('modals')
    
@endsection

@section('js')
    <script>


    //Search for the Location
    const locationSearch = document.querySelector("#findLocation");
    const locationResults = document.querySelector("#locationResults");

    locationSearch.addEventListener("input", function (e) {
        let value = e.target.value;
        if (value.length > 2) {
            const xhttp = new XMLHttpRequest();

            xhttp.onload = function () {
                locationResults.innerHTML = xhttp.responseText;
                locationResults.style.visibility = "visible";
                initLocationItems();
            };

            xhttp.open("POST", "/search/locations/");
            xhttp.setRequestHeader(
                "Content-type",
                "application/x-www-form-urlencoded"
            );
            xhttp.send(`search=${value}`);
        }
    });

    function initLocationItems() {
        //Gets all of the list items and adds an event listener to them
        //This has to be re-initialised everytime a result set is returned.
        document
            .querySelector("#locationResults")
            .querySelectorAll("li")
            .forEach(function (item) {
                item.addEventListener("click", function () {
                    //Get the information required
                    let name = this.getAttribute("data-name");
                    let id = this.getAttribute("data-id");
                    //Select the Elements
                    const cats = document.querySelector("#location_id");
                    cats.value = id;
                    locationResults.style.visibility = "hidden";
                    locationSearch.value = name;
                    getLocationInfo(id);
                });
            });
    }

    const locationInfo = document.querySelector("#locationInfo");

    function getLocationInfo(id) {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function () {
            locationInfo.innerHTML = xhttp.responseText;
        };

        xhttp.open("POST", "/location/preview/");
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(`id=${id}`);
    }

    </script>
@endsection