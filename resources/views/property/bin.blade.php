@extends('layouts.app')

@section('title', 'Recycle Bin | Property')

@section('css')
  
@endsection

@section('content')

    <x-wrappers.nav title="Property | Recycle Bin">
        <x-buttons.return :route="route('properties.index')" > Assets</x-buttons.return >
       {{--  @can('generatePDF', \App\Models\Asset::class)
            @if($assets->count() >1)
                <x-form.layout class="d-inline-block" :action="route('assets.pdf')">
                    <x-form.input type="hidden" name="assets" :label="false" formAttributes="required"
                                    :value="json_encode($assets->pluck('id'))"/>
                    <x-buttons.submit icon="fas fa-file-pdf">Generate Report</x-buttons.submit>
                </x-form.layout>
            @endif
        @endcan  --}}
        <a href="{{ route('documentation.index')."#collapseSixRecycleBin"}}"
               class="btn btn-sm  bg-yellow shadow-sm p-2 p-md-1" ><i
                    class="fas fa-question fa-sm text-dark-50 mr-lg-1" ></i ><span class="d-none d-lg-inline-block">Help</span></a >
                  
    </x-wrappers.nav>

    <x-handlers.alerts/>
    <section>
        <p class="mb-4">Below are properties that have been added to the Recycle Bin, please be aware that the recycle bin is differnet to the archive. Anything placed within the 
            recycle bin does not get caculated with the applications statistics including valuations, costs and depreciation. INstead this is the area where the mistakes or items
        that should be removed from all records</p>
        <!-- DataTales Example -->


        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive" id="table">
                    <table id="assetsTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-4 col-md-2"><small>Name</small></th>
                            <th class="col-3 col-md-2"><small>Type</small></th>
                            <th class="col-1 col-md-auto text-center"><small>Location</small></th>
                            <th class="text-center col-1 col-md-auto"><small>Value</small></th>
                            <th class="text-center col-2 col-md-auto"><small>Date</small></th>
                            <th class="text-center col-1 col-md-auto"><small>Current Value</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Depreciation (Years)</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Dep Charge</small></th>
                            <th class="text-right col-1"><small>Options</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th><small>Name</small></th>
                            <th><small>Type</small></th>
                            <th class="text-center"><small>Location</small></th>
                            <th class="text-center"><small>Value</small></th>  
                            <th class="text-center"><small>Date</small></th>  
                            <th class="text-center"><small>Current Value</small></th>
                            <th class="text-center"><small>Depreciation (Years)</small></th>
                            <th class="text-center"><small>Dep Charge</small></th>
                            <th class="text-right"><small>Options</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                            @foreach($properties as $property)
                            <tr>
                                <td class="text-left">{{$property->name}}</td>
                                <td class="text-left">
                                    @switch($property->type)
                                        @case(1)
                                            {{'Freehold Land'}}
                                            @break
                                        @case(2)
                                            {{'Freehold Building'}}
                                            @break
                                        @case(3)
                                            {{'Leasehold Land'}}
                                            @break
                                        @case(4)
                                            {{'Leasehold Building'}}
                                            @break
                                        @default
                                            {{'Unknown'}}
                                    @endswitch
                                </td>
                                <td class="text-center">
                                    @if($property->location()->exists())
                                        @if($property->location->photo()->exists())
                                            <img src="{{ asset($property->location->photo->path)}}" height="30px"
                                                 alt="{{$property->location->name}}"
                                                 title="{{ $property->location->name ?? 'Unnassigned'}}"/>
                                        @else
                                            {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($miscellanea->location->icon ?? '#666').'">'
                                                .strtoupper(substr($property->location->name ?? 'u', 0, 1)).'</span>' !!}
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center">£{{number_format($property->value, 2, '.', ',')}}</td>
                                <td class="text-center">{{\Carbon\Carbon::parse($property->date)->format('jS M Y')}}</td>
                                <td class="text-center">£{{number_format($property->depreciation_value(\Carbon\Carbon::now()), 2, '.', ',')}}</td>
                                <td class="text-center">{{$property->depreciation}} Years</td>
                                <td class="text-center">{{$property->depreciation}} Years</td>
                                <td class="text-right">
                                    <x-wrappers.table-settings>
                                        @can('delete', $property)
                                            <x-buttons.dropdown-item :route="route('property.restore', $property->id)">
                                                Restore
                                            </x-buttons.dropdown-item>
                                        @endcan
                                        @can('delete', $property)
                                            <x-form.layout method="POST" class="d-block p-0 m-0" :id="'form'.$property->id" :action="route('property.remove', $property->id)">
                                                <x-buttons.dropdown-item :data="$property->id" class="deleteBtn" >
                                                    Delete
                                                </x-buttons.dropdown-item>
                                            </x-form.layout>
                                        @endcan
                                    </x-wrappers.table-settings>
                                </td>
                            </tr>
                            @endforeach
                            @if($properties->count() == 0)
                            <tr>
                                <td colspan="9" class="text-center">The Recycle Bin is empty</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <x-paginate :model="$properties"/>
                </div>
            </div>
        </div>

        {{-- <div class="card shadow mb-3">
            <div class="card-body">
                <h4>Help with Assets</h4>
                <p>This area can be minimised and will contain a little help on the page that the user is currently
                    on.</p>
            </div>
        </div> --}}

    </section>
@endsection

@section('modals')
    <x-modals.permanentDelete model="property" />
@endsection

@section('js')
    <script>

        const deleteBtn = document.querySelectorAll('.deleteBtn');
        const deleteModal = new bootstrap.Modal(document.getElementById('permDeleteModal'));

        deleteBtn.forEach((item) => {
            item.addEventListener('click', function(){
                let model = document.querySelector('#model-id');
                let value = this.getAttribute('data-id');
                model.value = value;
                deleteModal.show();
            });
        })
       

        const confirmBtn = document.querySelector('#confirmPermDelete');

        confirmBtn.addEventListener('click', function(){
            let model = document.querySelector('#model-id').value;
            let formName = `#form${model}`;
            let form = document.querySelector(formName);
            form.submit();
            deleteModal.hide(); 
        });

    </script>
@endsection
