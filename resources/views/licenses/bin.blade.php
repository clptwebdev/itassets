@extends('layouts.app')

@section('title', 'View Bin License')


@section('content')
    <x-wrappers.nav title="License Recycle Bin">
        <x-buttons.return :route="route('licenses.index')">License</x-buttons.return>
    </x-wrappers.nav>
    <x-handlers.alerts/>
    <section>
        <p class="mt-5 mb-4">Below are Licenses belonging to the Central Learning Partnership Trust. You require
                             access to see
                             the Broadband assigned to the different locations. If you think you have the incorrect
                             permissions, please contact apollo@clpt.co.uk </p>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive" id="table">
                    <table id="assetsTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-4 col-md-2"><small>Name</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Supplier</small></th>
                            <th class="col-1 col-md-auto text-center"><small>Location</small></th>
                            <th class="col-3 col-md-2 text-center"><small>Purchase Cost</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Expiry</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Contact</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Status</small></th>
                            <th class="text-right col-1"><small>Options</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="col-4 col-md-2"><small>Name</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Supplier</small></th>
                            <th class="col-1 col-md-auto text-center"><small>Location</small></th>
                            <th class="col-3 col-md-2 text-center"><small>Purchase Cost</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Expiry</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Contact</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Status</small></th>
                            <th class="text-right col-1"><small>Options</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($licenses as $license)
                            <tr>
                                <td class="text-left">{{$license->name ?? 'No License Name'}}</td>
                                <td class="text-center">{{$license->supplier->name}}</td>
                                <td class="text-center">{{$license->location->name}}</td>
                                <td class="text-center">£{{number_format($license->purchased_cost, 2, '.', ',')}}</td>
                                <td class="text-center"><span>{{ \Illuminate\Support\Carbon::parse($license->expiry)->format('d-M-Y')}}
                                    </span><br>@if($license->isExpired())<small
                                        class='text-danger'>{{\Illuminate\Support\Carbon::parse($license->expiry)->diffForHumans()}}</small>
                                    @else
                                        <small
                                            class='text-success'>{{\Illuminate\Support\Carbon::parse($license->expiry)->diffForHumans()}}</small>
                                    @endif
                                </td>
                                <td class="text-center" href='mailto:{{$license->contact}}'>{{$license->contact}}</td>
                                <td class="text-center">
                                    @if($license->isExpired())
                                        <p class='text-danger'>Expired</p>
                                    @else
                                        <p class='text-success'>Valid</p>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <x-wrappers.table-settings>
                                        @can('viewAll', \App\Models\License::class)
                                            <x-buttons.dropdown-item :route="route('license.restore', $license->id)">
                                                Restore
                                            </x-buttons.dropdown-item>
                                        @endcan

                                        @can('delete', \App\Models\License::class)
                                            <x-form.layout method="POST" class="d-block p-0 m-0"
                                                           :id="'form'.$license->id"
                                                           :action="route('license.remove', $license->id)">
                                                <x-buttons.dropdown-item :data="$license->id" class="deleteBtn">
                                                    Delete
                                                </x-buttons.dropdown-item>
                                            </x-form.layout>
                                        @endcan
                                    </x-wrappers.table-settings>
                                </td>
                            </tr>
                        @endforeach
                        @if($licenses->count() == 0)
                            <tr>
                                <td colspan="9" class="text-center">No Licenses Returned</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    <x-paginate :model="$licenses"/>
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
