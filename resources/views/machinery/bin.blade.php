@extends('layouts.app')

@section('title', 'View Vehicle')


@section('content')
    <x-wrappers.nav title="Vehicle Recycle Bin">
        <x-buttons.return :route="route('vehicles.index')">Vehicle</x-buttons.return>
    </x-wrappers.nav>
    <x-handlers.alerts/>
    <section>
        <p class="mt-5 mb-4">Below is Vehicles belonging to the Central Learning Partnership Trust. You require
                             access to see
                             the Vehicles assigned to the different locations. If you think you have the incorrect
                             permissions, please contact apollo@clpt.co.uk </p>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive" id="table">
                    <table id="assetsTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-4 col-md-2"><small>Name</small></th>
                            <th class="col-4 col-md-2"><small>Registration</small></th>
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
                            <th class="col-4 col-md-2"><small>Registration</small></th>
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
                        @foreach($vehicles as $vehicle)
                            <tr>
                                <td class="text-left">{{$vehicle->name}}</td>
                                <td class="text-left">{{$vehicle->registration ?? 'N/A'}}</td>
                                <td class="text-center">£{{number_format($vehicle->purchased_cost, 2, '.', ',')}}</td>
                                <td class="text-center">{{ \Illuminate\Support\Carbon::parse($vehicle->purchased_date)->format('d-M-Y')}}</td>
                                <td class="text-center">{{$vehicle->supplier->name}}</td>
                                <td class="text-center">{{$vehicle->location->name}}</td>
                                <td class="text-center">
                                    £{{number_format($vehicle->depreciation_value_by_date(\Carbon\Carbon::now()), 2, '.', ',')}}
                                    <small>{{$vehicle->depreciation}} Years</small></td>
                                <td class="text-right">
                                    <x-wrappers.table-settings>
                                        @can('viewAll', \App\Models\Software::class)
                                            <x-buttons.dropdown-item :route="route('vehicle.restore', $vehicle->id)">
                                                Restore
                                            </x-buttons.dropdown-item>
                                        @endcan

                                        @can('delete', \App\Models\Software::class)
                                            <x-form.layout method="POST" class="d-block p-0 m-0"
                                                           :id="'form'.$vehicle->id"
                                                           :action="route('vehicle.remove', $vehicle->id)">
                                                <x-buttons.dropdown-item :data="$vehicle->id" class="deleteBtn">
                                                    Delete
                                                </x-buttons.dropdown-item>
                                            </x-form.layout>
                                        @endcan
                                    </x-wrappers.table-settings>
                                </td>
                            </tr>
                        @endforeach
                        @if($vehicles->count() == 0)
                            <tr>
                                <td colspan="9" class="text-center">No Vehicle Returned</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    <x-paginate :model="$vehicles"/>
                </div>
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
