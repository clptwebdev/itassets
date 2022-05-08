@extends('layouts.app')

@section('title', "View Software")

@section('css')

@endsection

@section('content')
    <x-wrappers.nav title="Show {{$software->name}} in Software">
        <x-buttons.return :route="route('softwares.index')"> Software</x-buttons.return>
        @can('update',$software)
            <x-buttons.edit :route="route('softwares.edit',$software->id)"/>
        @endcan

        @can('delete',$software)
            <x-form.layout method="DELETE" class="d-sm-inline-block" :id="'form'.$software->id"
                           :action="route('ffes.destroy', $software->id)">
                <x-buttons.delete formAttributes="data-id='{{$software->id}}'"/>
            </x-form.layout>
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
            <div class="tab-content border-left border-right border-bottom border-gray mb-4" id="myTabContent">
                <div class="tab-pane fade show p-2 pt-4 active" id="location" role="tabpanel"
                     aria-labelledby="location-tab">
                    <div class="row">
                        <div class="col-12 col-md-6 p-4 mb-3 ">
                            <table class="table table-sm table-bordered table-striped">
                                <tr>
                                    <th>Name:</th>
                                    <td>{{ucwords($software->name)}}</td>
                                </tr>
                                <tr>
                                    <th>Order No</th>
                                    <td>{{$software->order_no?? 'N/A'}}</td>
                                </tr>
                                <tr>
                                    <th>Purchased Date</th>
                                    <td>{{$software->purchased_date ?? 'N/A'}}</td>
                                </tr>
                                <tr>
                                    <th>Purchased Cost</th>
                                    <td>{{$software->purchased_cost ?? 'N/A'}}</td>
                                </tr>
                                <tr>
                                    <th>Donated</th>
                                    <td>{{$software->purchased_cost ?? 'N/A'}}</td>
                                </tr>
                                <tr>
                                    <th>Manufacturer</th>
                                    <td>{{$software->manufacturer->name ?? 'N/A'}}</td>
                                </tr>
                                <tr>
                                    <th>Supplier</th>
                                    <td>{{$software->supplier->name ?? 'N/A'}}</td>
                                </tr>
                            </table>
                            <div class="form-group">
                                {{$software->notes}}
                            </div>

                            <hr>
                            <h5>Finance</h5>
                            <?php
                            //If Date is > 1 September the Year is this Year else Year = Last Year

                            $now = \Carbon\Carbon::now();
                            $startDate = \Carbon\Carbon::parse('09/01/' . $now->format('Y'));
                            $nextYear = \Carbon\Carbon::now()->addYear()->format('Y');
                            $nextStartDate = \Carbon\Carbon::parse('09/01/' . \Carbon\Carbon::now()->addYear()->format('Y'));
                            $endDate = \Carbon\Carbon::parse('08/31/' . $nextYear);
                            if(! $startDate->isPast())
                            {
                                $startDate->subYear();
                                $endDate->subYear();
                                $nextStartDate->subYear();
                            }
                            $bf = $software->depreciation_value_by_date($startDate);
                            $cf = $software->depreciation_value_by_date($nextStartDate);
                            ?>

                            <p><strong>Cost B/Fwd (01/09/{{$startDate->format('Y')}}):</strong><br>
                                £{{number_format( (float) $bf, 2, '.', ',' )}}
                            </p>
                            <p><strong>Cost C/Fwd (31/08/{{$endDate->format('Y')}}):</strong><br>
                                £{{number_format( (float) $cf, 2, '.', ',' )}}
                            </p>
                            <p><strong>Depreciation B/Fwd (01/09/{{$startDate->format('Y')}}):</strong><br>
                                £{{number_format( (float) $software->purchased_cost - $bf, 2, '.', ',' )}}
                            </p>
                            <p><strong>Depreciation Charge:</strong><br>
                                £{{number_format( (float) $bf - $cf, 2, '.', ',' )}}
                            </p>
                            <p><strong>Depreciation C/Fwd (31/08/{{$endDate->format('Y')}}):</strong><br>
                                £{{number_format( (float) $software->purchased_cost - $cf, 2, '.', ',' )}}
                            </p>
                            <?php $prevYear = $startDate->subYear();?>
                            @if($prevYear >= $software->purchased_date)
                                <p><strong>NBV {{$prevYear->format('Y')}}:</strong><br>
                                    £{{number_format( (float) $software->depreciation_value_by_date($prevYear), 2, '.', ',' )}}
                                </p>
                            @endif
                            <?php $prevYear = $startDate->subYear();?>
                            @if($prevYear >= $software->purchased_date)
                                <p><strong>NBV {{$prevYear->format('Y')}}:</strong><br>
                                    £{{number_format( (float) $software->depreciation_value_by_date($prevYear), 2, '.', ',' )}}
                                </p>
                            @endif

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
            <div>
                <x-comments.comment-layout :asset="$software"/>
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
