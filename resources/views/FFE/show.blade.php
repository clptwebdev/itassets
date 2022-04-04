@extends('layouts.app')

@section('title', "View Asset Under Construction")

@section('css')

@endsection

@section('content')
    <x-wrappers.nav title="Show Furniture, Fixtures and Equipment">
        <x-buttons.return :route="route('ffes.index')"> FFE</x-buttons.return>
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
            </ul>

            <div class="tab-content border-left border-right border-bottom border-gray mb-4"
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
                                    <th>Status</th>
                                    <td>{{$ffe->status->name ?? 'Unknown'}}</td>
                                </tr>
                                <tr>
                                    <th>Purchased Date</th>
                                    <td>{{$ffe->purchased_date ?? 'N/A'}}</td>
                                </tr>
                                <tr>
                                    <th>Purchased Cost</th>
                                    <td>{{$ffe->purchased_cost ?? 'N/A'}}</td>
                                </tr>
                                <tr>
                                    <th>Donated</th>
                                    <td>@if($ffe->donated == 1) Yes @else No @endif</td>
                                </tr>
                                <tr>
                                    <th>Order No</th>
                                    <td>{{$ffe->order_no ?? 'N/A'}}</td>
                                </tr>
                                <tr>
                                    <th>Manufacturer</th>
                                    <td>{{$ffe->manufacturer->name ?? 'N/A'}}</td>
                                </tr>
                                <tr>
                                    <th>Supplier</th>
                                    <td>{{$ffe->supplier->name ?? 'N/A'}}</td>
                                </tr>
                                <tr>
                                    <th>Warranty (Months)</th>
                                    <td>{{$ffe->warranty ?? 'N/A'}}</td>
                                </tr>
                            </table>
                            <div class="form-group">
                                {{$ffe->notes}}
                            </div>

                            <hr>
                            <h5>Finance</h5>
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
                                $bf = $ffe->depreciation_value_by_date($startDate);
                                $cf = $ffe->depreciation_value_by_date($nextStartDate);
                            ?>
                            
                            <p><strong>Cost B/Fwd (01/09/{{$startDate->format('Y')}}):</strong><br>
                                £{{number_format( (float) $bf, 2, '.', ',' )}}
                            </p>
                            <p><strong>Cost C/Fwd (31/08/{{$endDate->format('Y')}}):</strong><br>
                                £{{number_format( (float) $cf, 2, '.', ',' )}}
                            </p>
                            <p><strong>Depreciation B/Fwd (01/09/{{$startDate->format('Y')}}):</strong><br>
                                £{{number_format( (float) $ffe->purchased_cost - $bf, 2, '.', ',' )}}
                            </p>
                            <p><strong>Depreciation Charge:</strong><br>
                                £{{number_format( (float) $bf - $cf, 2, '.', ',' )}}
                            </p>
                            <p><strong>Depreciation C/Fwd (31/08/{{$endDate->format('Y')}}):</strong><br>
                                £{{number_format( (float) $ffe->purchased_cost - $cf, 2, '.', ',' )}}
                            </p>
                            <?php $prevYear = $startDate->subYear();?>
                            @if($prevYear >= $ffe->purchased_date)
                            <p><strong>NBV {{$prevYear->format('Y')}}:</strong><br>
                                £{{number_format( (float) $ffe->depreciation_value_by_date($prevYear), 2, '.', ',' )}}
                            </p>
                            @endif
                            <?php $prevYear = $startDate->subYear();?>
                            @if($prevYear >= $ffe->purchased_date)
                            <p><strong>NBV {{$prevYear->format('Y')}}:</strong><br>
                                £{{number_format( (float) $ffe->depreciation_value_by_date($prevYear), 2, '.', ',' )}}
                            </p>
                            @endif

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

            <div class="">
                <x-comments.comment-layout :asset="$ffe"/>
            </div>
        </div>


    </div>


@endsection

@section('modals')
    <x-modals.add-comment :route="route('ffes.comment' ,$ffe->id)" :model="$ffe" title="ffe"/>
    <x-modals.edit-comment :model="$ffe"/>
    <x-modals.delete-comment/>
    <x-modals.delete/>
@endsection

@section('js')
    <script src="{{asset('js/comment.js')}}"></script>
    <script src="{{asset('js/delete.js')}}"></script>
@endsection
