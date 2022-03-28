@extends('layouts.app')

@section('title', "View Asset Under Construction")

@section('css')

@endsection

@section('content')
    <x-wrappers.nav title="Show Furniture, Fixtures and Equipment">
        <x-buttons.return :route="route('ffes.index')"> AUC</x-buttons.return>
        {{-- <x-buttons.reports :route="route('property.showPdf', $ffe->id)" /> --}}
        <x-buttons.edit :route="route('ffes.edit',$ffe->id)" />
        <x-form.layout method="DELETE" class="d-sm-inline-block"
                       :id="'form'.$ffe->id"
                       :action="route('ffes.destroy', $ffe->id)" >
            <x-buttons.delete formAttributes="data-id='{{$ffe->id}}'" /> 
        </x-form.layout >
        @can('generateShowPDF', $ffe)
            <x-buttons.reports :route="route('ffes.showPdf', $ffe->id)"/>
        @endcan
        
    </x-wrappers.nav >

    <x-handlers.alerts />
    
    <div class="container card">
        <div class="card-body">

            <ul id="tab-bar" class="nav nav-tabs">

                <li class="nav-item">
                    <a class="nav-link active" id="location-tab" data-bs-toggle="tab" href="#location" role="tab"
                       aria-controls="home" aria-selected="true">FFE Information</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="location-tab" data-bs-toggle="tab" href="#purchase" role="tab"
                       aria-controls="home" aria-selected="true">Purchase Information</a>
                </li>
            </ul>

            <div class="tab-content border-left border-right border-bottom border-gray"
                    id="myTabContent" >
                
                <div class="tab-pane fade show p-2 pt-4 active" id="location" role="tabpanel"
                     aria-labelledby="location-tab">
                    <div class="row">
                        <div class="col-12 col-md-6 p-4 mb-3 ">
                            <table class="table table-sm table-bordered table-striped">
                                <tr>
                                    <th>Name:</th>
                                    <td>{{ucwords($ffe->name)}}</td>
                                </tr>
                                <tr>
                                    <th>Serial No:</th>
                                    <td>{{$ffe->serial_no}}</td>
                                </tr>
                                <tr>
                                    <th>Manufacturer</th>
                                    <td>{{$ffe->manufacturer->name ?? 'N/A'}}</td>
                                </tr>
                            </table>

                            <div class="form-group">
                                {{$ffe->notes}}
                            </div>
                        </div>
                        <div class="col-12 col-md-6 p-4 mb-3 ">
                            <div id="locationInfo" class="bg-light p-4">
                                <div class="model_title text-center h4 mb-3">{{$ffe->location->name}}</div>
                                <div class="model_image p-4 d-flex justify-content-center align-items-middle">
                                    <img id="profileImage" src="{{ asset('images/svg/location-image.svg') }}"
                                         height="200px" alt="Select Profile Picture">
                                </div>
                                <div class="model_no py-2 px-4 text-center">
                                    {{$ffe->location->full_address(', ')}}
                                </div>
                                <div class="model_no py-2 px-4 text-center">
                                    {{$ffe->location->email}}
                                </div>
                                <div class="model_no py-2 px-4 text-center">
                                    {{ $ffe->location->telephone}}
                                </div>
                                <div class="model_no py-2 px-4 text-center">
                                    {{ $ffe->location->notes}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>


@endsection

@section('modals')


@endsection

@section('js')


@endsection
