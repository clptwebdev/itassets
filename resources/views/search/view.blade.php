@extends('layouts.app')

@section('title', "View Items")

@section('css')

@endsection

@section('content')
    <x-wrappers.nav title="Show Search Items">
        <x-buttons.return :route="route('dashboard')">Dashboard</x-buttons.return>
    </x-wrappers.nav>
    <x-handlers.alerts/>
    <section>
        <p class="mt-5 mb-4">Below are Items belonging to the Central Learning Partnership Trust.If You require
                             access to see
                             the orders assigned to the different locations. If you think you have the incorrect
                             permissions, please contact apollo@clpt.co.uk To View the information on a specific order
                             please click the name to continue.</p>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive" id="table">
                    <table id="assetsTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-4 col-md-auto ">Name</th>
                            <th class="text-center col-2 d-none d-xl-table-cell">
                                Created_at
                            </th>
                            <th class="text-center col-2 d-none d-xl-table-cell">Purchased
                                                                                 Cost
                            </th>
                            <th class="text-center col-4 d-none d-xl-table-cell">Location
                            </th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Name</th>
                            <th class="text-center">Created_at</th>
                            <th class="text-center">Purchased Cost</th>
                            <th class="text-center">Location</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($assets as $asset)
                            <tr>
                                <td class=""><a class='text-decoration-none' href='
                                                        @if($asset->getTable() == 'accessories'){{route('accessories.show', $asset->id)}} @endif @if($asset->getTable() == 'assets'){{route('assets.show', $asset->id)}} @endif @if($asset->getTable() == 'components'){{route('components.show', $asset->id)}} @endif @if($asset->getTable() == 'f_f_e_s'){{route('ffes.show', $asset->id)}} @endif @if($asset->getTable() == 'miscellaneas'){{route('miscellaneous.show', $asset->id)}} @endif @if($asset->getTable() == 'consumables'){{route('consumables.show', $asset->id)}} @endif'>{{$asset->name}}</a>
                                </td>
                                <td class="text-center">{{\Illuminate\Support\Carbon::parse($asset->created_at)->format('d-M-Y')}}</td>
                                <td class="text-center">{{$asset->purchased_cost}}</td>
                                <td class="text-center">{{$asset->location->name}}</td>
                            </tr>
                        @endforeach
                        @if($assets->count() == 0)
                            <tr>
                                <td colspan="9" class="text-center">No Order Assets Returned</td>
                            </tr>
                        @endif
                        </tbody>

                    </table>
                    <x-paginate :model="$assets"/>
                </div>
            </div>
        </div>
    </section>
@endsection


