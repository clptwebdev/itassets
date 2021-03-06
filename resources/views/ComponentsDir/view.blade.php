@extends('layouts.app')

@section('title', 'View Components')


@section('content')
    <x-wrappers.nav title="Components">
        @can('recycleBin', \App\Models\Component::class)
            <x-buttons.recycle :route="route('components.bin')" :count="\App\Models\Component::onlyTrashed()->count()"/>
        @endcan
        @can('create' , \App\Models\Component::class)
            <x-buttons.add :route="route('components.create')">Component</x-buttons.add>
        @endcan
        @can('viewAll', \App\Models\Component::class)
            @if ($components->count() == 1)
                <x-buttons.reports :route="route('components.showPdf', $components[0]->id)"/>
            @elseif($components->count() > 1)
                <x-form.layout class="d-inline-block" :action="route('components.pdf')">
                    <x-form.input type="hidden" name="components" :label="false" formAttributes="required"
                                  :value="json_encode($components->pluck('id'))"/>
                    <x-buttons.submit class="btn-blue">Generate Report</x-buttons.submit>
                </x-form.layout>
            @endif
            @if($components->count() >1)
                <x-buttons.export route="/exportcomponents"/>
            @endif
        @endcan
        @can('import' , \App\Models\Component::class)
            <x-buttons.import id="import"/>
        @endcan
    </x-wrappers.nav>
    <x-handlers.alerts/>
    @php
        $limit = auth()->user()->location_components()->orderBy('purchased_cost', 'desc')->pluck('purchased_cost')->first();
        $floor = auth()->user()->location_components()->orderBy('purchased_cost', 'asc')->pluck('purchased_cost')->first();
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
        <p class="mb-4">Below are the different Components stored in the management system. Each has
                        different options and locations can created, updated, and deleted.</p>
        @if($components->count() != 0)
            <x-filters.navigation model="Component" relations="components" table="Component"
                                  :filter="$filter"></x-filters.navigation>
            <x-filters.filter model="Component" relations="components" table="Component" :filter="$filter"
                              :locations="$locations" :statuses="$statuses"
                              :categories="$categories"></x-filters.filter>
        @endif
        <div class="card shadow mb-4">
            <div class="card-body">
                <table id="usersTable" class="table table-striped">
                    <thead>
                    <tr>
                        <th class="col-4 col-xl-2"><small>Name</small></th>
                        <th class="text-center"><small>Location</small></th>
                        <th class="text-center d-none d-sm-table-cell col-5 col-xl-2">
                            <small>Manufacturers</small>
                        </th>
                        <th class="d-none d-xl-table-cell"><small>Purchased Date</small></th>
                        <th class="d-none d-xl-table-cell"><small>Purchased Cost</small></th>
                        <th class="d-none d-xl-table-cell col-2"><small>Supplier</small></th>
                        <th class="text-center d-none d-xl-table-cell"><small>Status</small></th>
                        <th class="text-center d-none d-xl-table-cell"><small>Warranty</small></th>
                        <th class="text-right col-1"><small>Options</small></th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th><small>Name</small></th>
                        <th class="text-center"><small>Location</small></th>
                        <th class="text-center d-none d-sm-table-cell"><small>Manufacturers</small></th>
                        <th class="d-none d-xl-table-cell"><small>Purchased Date</small></th>
                        <th class="d-none d-xl-table-cell"><small>Purchased Cost</small></th>
                        <th class="d-none d-xl-table-cell"><small>Supplier</small></th>
                        <th class="text-center d-none d-xl-table-cell"><small>Status</small></th>
                        <th class="text-center d-none d-xl-table-cell"><small>Warranty</small></th>
                        <th class="text-right"><small>Options</small></th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @if($components->count() != 0)
                        @foreach($components as $component)

                            <tr>
                                <td>{{$component->name}}
                                    <br>
                                    <small>{{$component->serial_no}}</small>
                                </td>
                                <td class="text-center">
                                    @if(isset($component->location->photo->path))
                                        <img src="{{ asset($component->location->photo->path)}}" height="30px"
                                             alt="{{$component->location->name}}"
                                             title="{{ $component->location->name ?? 'Unnassigned'}}"/>
                                    @else
                                        {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($component->location->icon ?? '#666').'">'
                                            .strtoupper(substr($component->location->name ?? 'u', 0, 1)).'</span>' !!}
                                    @endif
                                </td>
                                <td class="text-center d-none d-sm-table-cell">{{$component->manufacturer->name ?? "N/A"}}</td>
                                <td class="d-none d-xl-table-cell">{{\Carbon\Carbon::parse($component->purchased_date)->format("d/m/Y")}}</td>
                                <td class="d-none d-xl-table-cell">{{$component->purchased_cost}}</td>
                                <td class="d-none d-xl-table-cell">{{$component->supplier->name ?? 'N/A'}}</td>
                                <td class="text-center">{{$component->status->name ??'N/A'}}</td>
                                @php $warranty_end = \Carbon\Carbon::parse($component->purchased_date)->addMonths($component->warranty);@endphp
                                <td class="text-center  d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
                                    {{ $component->warranty }} Months

                                    <br><small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }}
                                        Remaining</small>
                                </td>
                                <?php $data = $component;?>
                                <td class="text-right">
                                    <x-wrappers.table-settings>
                                        @can('view', $data)
                                            <x-buttons.dropdown-item :route="route('components.show', $data->id)">
                                                View
                                            </x-buttons.dropdown-item>
                                        @endcan
                                        @can('update', $data)
                                            <x-buttons.dropdown-item :route=" route('components.edit', $data->id)">
                                                Edit
                                            </x-buttons.dropdown-item>
                                        @endcan
                                        @can('delete', $data)
                                            <x-form.layout method="DELETE" class="d-block p-0 m-0"
                                                           :id="'form'.$data->id"
                                                           :action="route('components.destroy', $data->id)">
                                                <x-buttons.dropdown-item :data="$data->id" class="deleteBtn">
                                                    Delete
                                                </x-buttons.dropdown-item>
                                            </x-form.layout>
                                        @endcan
                                    </x-wrappers.table-settings>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <td colspan="10" class="text-center">No Components Returned</td>
                    @endif
                    </tbody>
                </table>
                <x-paginate :model="$components"/>
            </div>
        </div>
        <div class="card shadow mb-3">
            <div class="card-body">
                <h4>Help with Components </h4>
                <p>Click <a href="{{route("documentation.index").'#collapseNineComponent'}}">here</a> for
                   the
                   Documentation on Components on Importing ,Exporting , Adding , Removing!</p>

            </div>
        </div>

    </section>

@endsection

@section('modals')
    <x-modals.delete> Component</x-modals.delete>
    <x-modals.import route="/importcomponents"/>
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
