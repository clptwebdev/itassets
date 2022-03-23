@extends('layouts.app')

@section('title', "View Property")

@section('css')

@endsection

@section('content')
    <x-wrappers.nav title="View Property" >
        <x-buttons.return :route="route('properties.index')" > Properties</x-buttons.return >
        {{-- <x-buttons.reports :route="route('property.showPdf', $property->id)" /> --}}
        <x-buttons.edit :route="route('properties.edit',$property->id)" />
        <x-form.layout method="DELETE" class="d-sm-inline-block"
                       :id="'form'.$property->id"
                       :action="route('properties.destroy', $property->id)" >
            <x-buttons.delete formAttributes="data-id='{{$property->id}}'" /> 
        </x-form.layout >
        @can('generateShowPDF', $property)
            <x-buttons.reports :route="route('properties.showPdf', $property->id)"/>
        @endcan
    </x-wrappers.nav >

    <x-handlers.alerts />
    
    <div class="container card">
        <div class="card-body">


            <ul id="tab-bar" class="nav nav-tabs" >

                <li class="nav-item" >
                    <a class="nav-link active" id="location-tab" data-toggle="tab" href="#location" role="tab"
                        aria-controls="home" aria-selected="true" >Property Information</a >
                </li >
            </ul >
            <div class="tab-content border-left border-right border-bottom border-gray mb-4"
                    id="myTabContent" >
                
                <div class="tab-pane fade show p-2 pt-4 active" id="location" role="tabpanel"
                        aria-labelledby="location-tab" >
                    <div class="row" >
                        <div class="col-12 col-md-6 p-4 mb-3 " >
                            <h4 class="font-weight-600 mb-4">{{$property->name}}</h4>
                            <p><strong>Type:</strong> {{$property->getType()}}</p>
                            <p><strong>Depreciation:</strong> {{$property->depreciation}} Years</p>
                            <p><strong>Date Occupied:</strong> {{\Carbon\Carbon::parse($property->purchased_date)->format('jS M Y')}}</p>
                            <p><strong>Value (At Time of Purchase):</strong><br>£{{number_format( (float) $property->purchased_cost, 2, '.', ',' )}}</p>

                <hr>
                            <?php
                                //If Date is > 1 September the Year is this Year else Year = Last Year
                
                                $now = \Carbon\Carbon::now();
                                $startDate = \Carbon\Carbon::parse('09/01/'.$now->format('Y'));
                                $nextYear = \Carbon\Carbon::now()->addYear()->format('Y');
                                $nextStartDate = \Carbon\Carbon::parse('09/01/'.\Carbon\Carbon::now()->addYear()->format('Y'));
                                $endDate = \Carbon\Carbon::parse('08/31/'.$nextYear);
                                if(!$startDate->isPast()){
                                    $startDate->subYear();
                                    $endDate->subYear();
                                    $nextStartDate->subYear();
                                }
                                $bf = $property->depreciation_value_by_date($startDate);
                                $cf = $property->depreciation_value_by_date($nextStartDate);
                            ?>
                            
                            <p><strong>Cost B/Fwd (01/09/{{$startDate->format('Y')}}):</strong><br>
                                £{{number_format( (float) $bf, 2, '.', ',' )}}
                            </p>
                            <p><strong>Cost C/Fwd (31/08/{{$endDate->format('Y')}}):</strong><br>
                                £{{number_format( (float) $cf, 2, '.', ',' )}}
                            </p>
                            <p><strong>Depreciation B/Fwd (01/09/{{$startDate->format('Y')}}):</strong><br>
                                £{{number_format( (float) $property->purchased_cost - $bf, 2, '.', ',' )}}
                            </p>
                            <p><strong>Depreciation Charge:</strong><br>
                                £{{number_format( (float) $bf - $cf, 2, '.', ',' )}}
                            </p>
                            <p><strong>Depreciation C/Fwd (31/08/{{$endDate->format('Y')}}):</strong><br>
                                £{{number_format( (float) $property->purchased_cost - $cf, 2, '.', ',' )}}
                            </p>
                            <?php $prevYear = $startDate->subYear();?>
                            @if($prevYear >= $property->purchased_date)
                            <p><strong>NBV {{$prevYear->format('Y')}}:</strong><br>
                                £{{number_format( (float) $property->depreciation_value_by_date($prevYear), 2, '.', ',' )}}
                            </p>
                            <?php $prevYear = $startDate->subYear();?>
                            <p><strong>NBV {{$prevYear->format('Y')}}:</strong><br>
                                £{{number_format( (float) $property->depreciation_value_by_date($prevYear), 2, '.', ',' )}}
                            </p>
                            @endif


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

            <div class="">
                <x-comments.comment-layout :asset="$property"/>
            </div>
        </div>
        

    </div>


@endsection

@section('modals')
    
    <x-modals.add-comment :route="route('property.comment' ,$property->id)" :model="$property" title="property"/>
    <x-modals.edit-comment :model="$property"/>
    <x-modals.delete-comment/>
    <x-modals.delete/>

    @endsection

@section('js')
    <script src="{{asset('js/comment.js')}}"></script>
    <script src="{{asset('js/delete.js')}}"></script>
@endsection
