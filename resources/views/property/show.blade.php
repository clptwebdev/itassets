@extends('layouts.app')

@section('title', "View Property")

@section('css')

@endsection

@section('content')
    <x-wrappers.nav title="Property" >
        <x-buttons.return :route="route('properties.index')" > Properties</x-buttons.return >
        {{-- <x-buttons.reports :route="route('property.showPdf', $property->id)" /> --}}
        <x-buttons.edit :route="route('properties.edit',$property->id)" />
        <x-form.layout method="DELETE" class="d-sm-inline-block"
                       :id="'form'.$property->id"
                       :action="route('properties.destroy', $property->id)" >
            <x-buttons.delete formAttributes="data-id='{{$property->id}}'" /> 
        </x-form.layout >
        
    </x-wrappers.nav >

    <x-handlers.alerts />
    
    <div class="container card">
        <div class="card-body">
            <x-form.errors />
            <x-handlers.alerts />


            <ul id="tab-bar" class="nav nav-tabs" >

                <li class="nav-item" >
                    <a class="nav-link active" id="location-tab" data-toggle="tab" href="#location" role="tab"
                        aria-controls="home" aria-selected="true" >Property Information</a >
                </li >
            </ul >
            <div class="tab-content border-left border-right border-bottom border-gray"
                    id="myTabContent" >
                
                <div class="tab-pane fade show p-2 pt-4 active" id="location" role="tabpanel"
                        aria-labelledby="location-tab" >
                    <div class="row" >
                        <div class="col-12 col-md-6 p-4 mb-3 " >
                            <h4 class="font-weight-600 mb-4">{{$property->name}}</h4>
                            <p><strong>Type:</strong> {{$property->getType()}}</p>
                            <p><strong>Depreciation:</strong> {{$property->depreciation}} Years</p>
                            <p><strong>Date Occupied:</strong><br>{{\Carbon\Carbon::parse($property->date)->format('jS M Y')}}</p>
                            <p><strong>Value (At Time of Purchase):</strong><br>£{{number_format( (float) $property->value, 2, '.', ',' )}}</p>

                
                            <?php
                                //If Date is > 1 September the Year is this Year else Year = Last Year
                
                                $now = \Carbon\Carbon::now();
                                $startDate = \Carbon\Carbon::parse('09/01/'.$now->format('Y'));
                                $endDate = \Carbon\Carbon::parse('08/31/'.\Carbon\Carbon::now()->addYear()->format('Y'));
                                if(!$startDate->isPast()){
                                    $startDate->subYear();
                                    $endDate->subYear();
                                }

                                $bf = $property->depreciation_value($startDate);
                                $cf = $property->depreciation_value($endDate);
                            ?>
                            
                            <p><strong>Current Value ({{$startDate->format('d\/m\/Y')}}):</strong><br>
                                £{{number_format( (float) $bf, 2, '.', ',' )}}
                            </p>
                            <p><strong>Depreciation B/Fwd ({{$startDate->format('d\/m\/Y')}}):</strong><br>
                                £{{number_format( (float) $property->value - $bf, 2, '.', ',' )}}
                            </p>
                            <p><strong>Depreciation C/Fwd ({{$endDate->format('d\/m\/Y')}}):</strong><br>
                                £{{number_format( (float) $bf - $cf, 2, '.', ',' )}}
                            </p>
                            <?php $prevYear = $startDate->subYear();?>
                            <p><strong>NBV {{$prevYear->format('Y')}}:</strong><br>
                                £{{number_format( (float) $property->depreciation_value($prevYear), 2, '.', ',' )}}
                            </p>
                            <?php $prevYear = $startDate->subYear();?>
                            <p><strong>NBV {{$prevYear->format('Y')}}:</strong><br>
                                £{{number_format( (float) $property->depreciation_value($prevYear), 2, '.', ',' )}}
                            </p>


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
                                    {{$property->location->full_address(', ')}}
                                </div >
                                <div class="model_no py-2 px-4 text-center" >
                                    {{$property->location->email}}
                                </div >
                                <div class="model_no py-2 px-4 text-center" >
                                    {{ $property->location->telephone}}
                                </div >
                                <div class="model_no py-2 px-4 text-center" >
                                    {{ $property->location->notes}}
                                </div >
                            </div >
                        </div >
                    </div >
                </div >

            </div >
        </div>
        

    </div>


@endsection

@section('modals')
    

@endsection

@section('js')
    

@endsection
