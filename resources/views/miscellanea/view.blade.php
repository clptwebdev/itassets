@extends('layouts.app')

@section('title', 'View Miscellaneous')

@section('content')
    <x-wrappers.nav title="Miscellaneous">
        @can('viewAny', \App\Models\Miscellanea::class)
            <x-buttons.recycle :route="route('miscellaneous.bin')"
                               :count="\App\Models\Miscellanea::onlyTrashed()->count()"/>
        @endcan
        @can('create', \App\Models\Miscellanea::class)
            <x-buttons.add :route="route('miscellaneous.create')">Miscellanea</x-buttons.add>

        @endcan
        @can('viewAny', \App\Models\Miscellanea::class)
            @if ($miscellaneous->count() == 1)
                <x-buttons.reports :route="route('miscellaneous.showPdf', $miscellaneous[0]->id)"/>
            @else
                <x-form.layout class="d-inline-block" :action="route('miscellaneous.pdf')">
                    <x-form.input type="hidden" name="miscellaneous" :label="false" formAttributes="required"
                                  :value="json_encode($miscellaneous->pluck('id'))"/>
                    <x-buttons.submit>Generate Report</x-buttons.submit>
                </x-form.layout>
            @endif
            @if($miscellaneous->count() >1)
                <x-buttons.export route="/exportmiscellaneous"/>
            @endif
        @endcan
        @can('create', \App\Models\Miscellanea::class)
            <x-buttons.import id="import"/>
        @endcan
    </x-wrappers.nav>
    <x-handlers.alerts/>
    @php

        $limit = auth()->user()->location_miscellaneous()->orderBy('purchased_cost', 'desc')->pluck('purchased_cost')->first();
        $floor = auth()->user()->location_miscellaneous()->orderBy('purchased_cost', 'asc')->pluck('purchased_cost')->first();
    if(session()->has('amount')){
        $amount = str_replace('£', '', session('amount'));
        $amount = explode(' - ', $amount);
        $start_value = intval($amount[0]);
        $end_value = intval($amount[1]);
    }else{
        $start_value = $floor;
        $end_value = $limit;
    }
    @endphp
    <section>
        <p class="mb-4">Below are the different miscellaneous stored in the management system. Each has
                        different options and locations can created, updated, and deleted.</p>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="usersTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th><small>Name</small></th>
                            <th class="text-center"><small>Location</small></th>
                            <th class="text-center"><small>Manufacturers</small></th>
                            <th><small>Date</small></th>
                            <th><small>Cost</small></th>
                            <th><small>Supplier</small></th>
                            <th class="text-center"><small>Status</small></th>
                            <th class="text-center"><small>Warranty</small></th>
                            <th class="text-right"><small>Options</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th><small>Name</small></th>
                            <th class="text-center"><small>Location</small></th>
                            <th class="text-center"><small>Manufacturers</small></th>
                            <th><small>Purchased Date</small></th>
                            <th><small>Purchased Cost</small></th>
                            <th><small>Supplier</small></th>
                            <th class="text-center"><small>Status</small></th>
                            <th class="text-center"><small>Warranty</small></th>
                            <th class="text-right"><small>Options</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($miscellaneous as $miscellanea)

                            <tr>
                                <td>{{$miscellanea->name}}
                                    <br>
                                    <small>{{$miscellanea->serial_no}}</small>
                                </td>
                                <td class="text-center">
                                    @if($miscellanea->location->photo()->exists())
                                        <img src="{{ asset($miscellanea->location->photo->path)}}" height="30px"
                                             alt="{{$miscellanea->location->name}}"
                                             title="{{ $miscellanea->location->name ?? 'Unnassigned'}}"/>
                                    @else
                                        {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($miscellanea->location->icon ?? '#666').'">'
                                            .strtoupper(substr($miscellanea->location->name ?? 'u', 0, 1)).'</span>' !!}
                                    @endif
                                </td>
                                <td class="text-center">{{$miscellanea->manufacturer->name ?? "N/A"}}</td>
                                <td data-sort="{{ strtotime($miscellanea->purchased_date)}}">{{\Carbon\Carbon::parse($miscellanea->purchased_date)->format("d/m/Y")}}</td>
                                <td>£{{$miscellanea->purchased_cost}}</td>
                                <td>{{$miscellanea->supplier->name ?? 'N/A'}}</td>
                                <td class="text-center" style="color: {{$miscellanea->status->colour ?? '#666'}};">
                                    <i class="{{$miscellanea->status->icon ?? 'fas fa-circle'}}"></i> {{ $miscellanea->status->name ?? 'N/A' }}
                                </td>
                                @php $warranty_end = \Carbon\Carbon::parse($miscellanea->purchased_date)->addMonths($miscellanea->warranty);@endphp
                                <td class="text-center  d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
                                    {{ $miscellanea->warranty }} Months

                                    <br><small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }}
                                        Remaining</small></td>
                                <td class="text-right">
                                    <div class="dropdown no-arrow">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                           id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div
                                            class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">miscellanea Options:</div>
                                            <a href="{{ route('miscellaneous.show', $miscellanea->id) }}"
                                               class="dropdown-item">View</a>
                                            @can('delete', $miscellanea)
                                                <a href="{{ route('miscellaneous.restore', $miscellanea->id) }}"
                                                   class="dropdown-item">Restore</a>
                                                <form class="d-block" id="form{{$miscellanea->id}}"
                                                      action="{{ route('miscellaneous.remove', $miscellanea->id) }}"
                                                      method="POST">
                                                    @csrf
                                                    <a class="deleteBtn dropdown-item" href="#"
                                                       data-id="{{$miscellanea->id}}">Delete</a>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow mb-3">
            <div class="card-body">
                <h4>Help with miscellaneous</h4>
                <p>This area can be minimised and will contain a little help on the page that the miscellanea is
                   currently
                   on.</p>
            </div>
        </div>

    </section>
@endsection

@section('modals')
    <x-modals.delete>Miscellanea</x-modals.delete>
    <x-modals.import route="/importmiscellaneous"/>
@endsection

@section('js')

    <script src="{{asset('js/delete.js')}}"></script>
    <script src="{{asset('js/import.js')}}"></script>
    <script src="{{asset('js/filter.js')}}"></script>
    <script>
        let sliderMin = document.querySelector('#customRange1');
        let sliderMax = document.querySelector('#customRange2');
        let sliderMinValue = document.querySelector('#minRange');
        let sliderMaxValue = document.querySelector('#maxRange');

        //setting slider ranges
        sliderMin.setAttribute('min', {{ floor($start_value)}});
        sliderMin.setAttribute('max', {{ round($end_value)}});
        sliderMax.setAttribute('min', {{ floor($start_value)}});
        sliderMax.setAttribute('max', {{ round($end_value)}});
        sliderMax.value = {{ round($end_value)}};
        sliderMin.value = {{ floor($start_value)}};

        sliderMinValue.innerHTML = {{ floor($start_value)}};
        sliderMaxValue.innerHTML = {{ round($end_value)}};

        sliderMin.addEventListener('input', function () {
            sliderMinValue.innerHTML = sliderMin.value;
            sliderMaxValue.innerHTML = sliderMax.value;

        });
        sliderMax.addEventListener('input', function () {
            sliderMaxValue.innerHTML = sliderMax.value;
            sliderMinValue.innerHTML = sliderMin.value;
            sliderMin.setAttribute('max', sliderMax.value);


        });
    </script>

@endsection
