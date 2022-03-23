@extends('layouts.app')

@section('title', "View Asset Under Construction")

@section('css')

@endsection

@section('content')
    <x-wrappers.nav title="Show Asset Under Construction" >
        <x-buttons.return :route="route('aucs.index')" > AUC</x-buttons.return >
        {{-- <x-buttons.reports :route="route('property.showPdf', $auc->id)" /> --}}
        <x-buttons.edit :route="route('aucs.edit',$auc->id)" />
        <x-form.layout method="DELETE" class="d-sm-inline-block"
                       :id="'form'.$auc->id"
                       :action="route('aucs.destroy', $auc->id)" >
            <x-buttons.delete formAttributes="data-id='{{$auc->id}}'" /> 
        </x-form.layout >
        @can('generateShowPDF', $auc)
            <x-buttons.reports :route="route('aucs.showPdf', $auc->id)"/>
        @endcan
        
    </x-wrappers.nav >

    <x-handlers.alerts />
    
    <div class="container card">
        <div class="card-body">

            <ul id="tab-bar" class="nav nav-tabs" >

                <li class="nav-item" >
                    <a class="nav-link active" id="location-tab" data-toggle="tab" href="#location" role="tab"
                        aria-controls="home" aria-selected="true" >Asset Under Construction Information</a >
                </li >
            </ul >
            <div class="tab-content border-left border-right border-bottom border-gray"
                    id="myTabContent" >
                
                <div class="tab-pane fade show p-2 pt-4 active" id="location" role="tabpanel"
                        aria-labelledby="location-tab" >
                    <div class="row" >
                        <div class="col-12 col-md-6 p-4 mb-3 " >
                            <h4 class="font-weight-600 mb-4">{{$auc->name}}</h4>
                            <p><strong>Type:</strong> {{$auc->getType()}}</p>
                            <p><strong>Depreciation:</strong> {{$auc->depreciation}} Years</p>
                            <p><strong>Date Occupied:</strong><br>{{\Carbon\Carbon::parse($auc->purchased_date)->format('jS M Y')}}</p>
                            <p><strong>Value (At Time of Purchase):</strong><br>£{{number_format( (float) $auc->purchased_cost, 2, '.', ',' )}}</p>
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
                                $bf = $auc->depreciation_value_by_date($startDate);
                                $cf = $auc->depreciation_value_by_date($nextStartDate);
                            ?>
                            
                            <p><strong>Cost B/Fwd (01/09/{{$startDate->format('Y')}}):</strong><br>
                                £{{number_format( (float) $bf, 2, '.', ',' )}}
                            </p>
                            <p><strong>Cost C/Fwd (31/08/{{$endDate->format('Y')}}):</strong><br>
                                £{{number_format( (float) $cf, 2, '.', ',' )}}
                            </p>
                            <p><strong>Depreciation B/Fwd (01/09/{{$startDate->format('Y')}}):</strong><br>
                                £{{number_format( (float) $auc->purchased_cost - $bf, 2, '.', ',' )}}
                            </p>
                            <p><strong>Depreciation Charge:</strong><br>
                                £{{number_format( (float) $bf - $cf, 2, '.', ',' )}}
                            </p>
                            <p><strong>Depreciation C/Fwd (31/08/{{$endDate->format('Y')}}):</strong><br>
                                £{{number_format( (float) $auc->purchased_cost - $cf, 2, '.', ',' )}}
                            </p>
                            <?php $prevYear = $startDate->subYear();?>
                            @if($prevYear >= $auc->purchased_date)
                            <p><strong>NBV {{$prevYear->format('Y')}}:</strong><br>
                                £{{number_format( (float) $auc->depreciation_value_by_date($prevYear), 2, '.', ',' )}}
                            </p>
                            @endif
                            <?php $prevYear = $startDate->subYear();?>
                            @if($prevYear >= $auc->purchased_date)
                            <p><strong>NBV {{$prevYear->format('Y')}}:</strong><br>
                                £{{number_format( (float) $auc->depreciation_value_by_date($prevYear), 2, '.', ',' )}}
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
                                    {{$auc->location->full_address(', ')}}
                                </div >
                                <div class="model_no py-2 px-4 text-center" >
                                    {{$auc->location->email}}
                                </div >
                                <div class="model_no py-2 px-4 text-center" >
                                    {{ $auc->location->telephone}}
                                </div >
                                <div class="model_no py-2 px-4 text-center" >
                                    {{ $auc->location->notes}}
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
