@extends('layouts.app')

@section('title', 'View Machinery')


@section('content')
    <x-wrappers.nav title="Machinery Recycle Bin">
        <x-buttons.return :route="route('machineries.index')">Machinery</x-buttons.return>
    </x-wrappers.nav>
    <x-handlers.alerts/>
    <section>
        <p class="mt-5 mb-4">Below are machineries belonging to the Central Learning Partnership Trust. You require
                             access to see
                             the machineries assigned to the different locations. If you think you have the incorrect
                             permissions, please contact apollo@clpt.co.uk </p>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                    <table id="assetsTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-4 col-md-2"><small>Name</small></th>
                            <th class="col-4 col-md-2"><small>Description</small></th>
                            <th class="col-3 col-md-2 text-center"><small>Purchase Cost</small></th>
                            <th class="text-center col-2 col-md-auto"><small>Purchase Date</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Supplier</small></th>
                            <th class="col-1 col-md-auto text-center"><small>Location</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Depreciation (Years)</small>
                            </th>
                            <th class="text-right col-1"><small>Options</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="col-4 col-md-2"><small>Name</small></th>
                            <th class="col-4 col-md-2"><small>Description</small></th>
                            <th class="col-3 col-md-2 text-center"><small>Purchase Cost</small></th>
                            <th class="text-center col-2 col-md-auto"><small>Purchase Date</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Supplier</small></th>
                            <th class="col-1 col-md-auto text-center"><small>Location</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Depreciation (Years)</small>
                            </th>
                            <th class="text-right col-1"><small>Options</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($machineries as $machinery)
                            <tr>
                                <td class="text-left">{{$machinery->name}}</td>
                                <td class="text-left">{{$machinery->dexcription ?? 'N/A'}}</td>
                                <td class="text-center">£{{number_format($machinery->purchased_cost, 2, '.', ',')}}</td>
                                <td class="text-center">{{ \Illuminate\Support\Carbon::parse($machinery->purchased_date)->format('d-M-Y')}}</td>
                                <td class="text-center">{{$machinery->supplier->name}}</td>
                                <td class="text-center">{{$machinery->location->name}}</td>
                                <td class="text-center">
                                    £{{number_format($machinery->depreciation_value_by_date(\Carbon\Carbon::now()), 2, '.', ',')}}<br>
                                    <small>{{$machinery->depreciation}} Years</small></td>
                                <td class="text-right">
                                    <x-wrappers.table-settings>
                                        @can('viewAll', \App\Models\Machinery::class)
                                            <x-buttons.dropdown-item
                                                :route="route('machinery.restore', $machinery->id)">
                                                Restore
                                            </x-buttons.dropdown-item>
                                        @endcan

                                        @can('delete', \App\Models\Machinery::class)
                                            <x-form.layout method="POST" class="d-block p-0 m-0"
                                                           :id="'form'.$machinery->id"
                                                           :action="route('machinery.remove', $machinery->id)">
                                                <x-buttons.dropdown-item :data="$machinery->id" class="deleteBtn">
                                                    Delete
                                                </x-buttons.dropdown-item>
                                            </x-form.layout>
                                        @endcan
                                    </x-wrappers.table-settings>
                                </td>
                            </tr>
                        @endforeach
                        @if($machineries->count() == 0)
                            <tr>
                                <td colspan="9" class="text-center">No Machinery Returned</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    <x-paginate :model="$machineries"/>
            </div>
        </div>
    </section>
@endsection
@section('modals')
    <x-modals.delete archive="true"/>
@endsection

@section('js')
    <script src="{{asset('js/delete.js')}}"></script>

@endsection
