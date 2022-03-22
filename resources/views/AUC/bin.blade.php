@extends('layouts.app')

@section('title', 'Recycle Bin | Assets Under Construction')

@section('css')

@endsection

@section('content')

    <x-wrappers.nav title="Assets Under Construction | Recycle Bin">
        <x-buttons.return :route="route('aucs.index')"> Assets Under Construction</x-buttons.return>
        <a href="{{ route('documentation.index')."#collapseSixRecycleBin"}}"
           class="btn btn-sm  bg-yellow shadow-sm p-2 p-md-1"><i class="fas fa-question fa-sm text-dark-50 mr-lg-1"></i><span
                class="d-none d-lg-inline-block">Help</span></a>

    </x-wrappers.nav>

    <x-handlers.alerts/>
    <section>
        <p class="mb-4">Below are the Assets Under Construction that have been added to the Recycle Bin, please be aware
                        that the recycle bin is differnet to the archive. Anything placed within the
                        recycle bin does not get caculated with the applications statistics including valuations, costs
                        and depreciation. INstead this is the area where the mistakes or items
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
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Depreciation (Years)</small>
                            </th>
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
                        @foreach($aucs as $auc)
                            <tr>
                                <td class="text-left">{{$auc->name}}</td>
                                <td class="text-left">
                                    @switch($auc->type)
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
                                    @if($auc->location()->exists())
                                        @if($auc->location->photo()->exists())
                                            <img src="{{ asset($auc->location->photo->path)}}" height="30px"
                                                 alt="{{$auc->location->name}}"
                                                 title="{{ $auc->location->name ?? 'Unnassigned'}}"/>
                                        @else
                                            {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($miscellanea->location->icon ?? '#666').'">'
                                                .strtoupper(substr($auc->location->name ?? 'u', 0, 1)).'</span>' !!}
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center">£{{number_format($auc->value, 2, '.', ',')}}</td>
                                <td class="text-center">{{\Carbon\Carbon::parse($auc->date)->format('jS M Y')}}</td>
                                <td class="text-center">
                                    £{{number_format($auc->depreciation_value(\Carbon\Carbon::now()), 2, '.', ',')}}</td>
                                <td class="text-center">{{$auc->depreciation}} Years</td>
                                <td class="text-center">{{$auc->depreciation}} Years</td>
                                <td class="text-right">
                                    <x-wrappers.table-settings>
                                        @can('delete', $auc)
                                            <x-buttons.dropdown-item :route="route('auc.restore', $auc->id)">
                                                Restore
                                            </x-buttons.dropdown-item>
                                        @endcan
                                        @can('delete', $auc)
                                            <x-form.layout method="POST" class="d-block p-0 m-0" :id="'form'.$auc->id"
                                                           :action="route('auc.remove', $auc->id)">
                                                <x-buttons.dropdown-item :data="$auc->id" class="deleteBtn">
                                                    Delete
                                                </x-buttons.dropdown-item>
                                            </x-form.layout>
                                        @endcan
                                    </x-wrappers.table-settings>
                                </td>
                            </tr>
                        @endforeach
                        @if($aucs->count() == 0)
                            <tr>
                                <td colspan="9" class="text-center">The Recycle Bin is empty</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    <x-paginate :model="$aucs"/>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('modals')
    <x-modals.permanentDelete model="Assets Under Construction"/>
@endsection

@section('js')
    <script src="{{asset('js/permanent-delete.js')}}"></script>
@endsection
