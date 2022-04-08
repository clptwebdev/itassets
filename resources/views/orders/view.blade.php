@extends('layouts.app')

@section('title', 'View Orders')


@section('content')
    <x-wrappers.nav title="Orders"></x-wrappers.nav>
    <x-handlers.alerts/>
    <section>
        <p class="mt-5 mb-4">Below are orders belonging to the Central Learning Partnership Trust.If You require
                             access to see
                             the orders assigned to the different locations. If you think you have the incorrect
                             permissions, please contact apollo@clpt.co.uk </p>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive" id="table">
                    <table id="assetsTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-4 col-md-2"><small>Order Number</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Items</small></th>
                            <th class="col-1 col-md-auto text-center"><small>Item Values</small></th>
                            <th class="col-3 col-md-2 text-center"><small>Purchase Date</small></th>
                            <th class="text-right col-1"><small>Options</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="col-4 col-md-2"><small>Order Number</small></th>
                            <th class="text-center col-1 d-none d-xl-table-cell"><small>Items</small></th>
                            <th class="col-1 col-md-auto text-center"><small>Item Values</small></th>
                            <th class="col-3 col-md-2 text-center"><small>Purchase Date</small></th>
                            <th class="text-right col-1"><small>Options</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($orders as $key => $order)
                            <tr>
                                <td class="text-left">{{$key ?? 'No Order Number'}}</td>
                                <td class="text-center">{{$order['items'] ?? 'N/A'}}</td>
                                <td class="text-center">£{{$order['value'] ?? 'No Cost'}}</td>
                                <td class="text-center">{{ \Illuminate\Support\Carbon::parse($order['purchased_date'])->format('d-M-Y')}}</td>
                                <td class="text-right">
                                    <x-wrappers.table-settings>
                                        <x-form.layout method="POST" :action="route('order.show', $key)">
                                            <input type="hidden" value="{{$key}}" name='order'/>
                                            <x-buttons.dropdown-item :route="route('order.show', $key)">
                                                View
                                            </x-buttons.dropdown-item>
                                        </x-form.layout>


                                    </x-wrappers.table-settings>
                                </td>
                            </tr>
                        @endforeach
                        {{--                        @if($orders->count() == 0)--}}
                        {{--                            <tr>--}}
                        {{--                                <td colspan="9" class="text-center">No Orders Returned</td>--}}
                        {{--                            </tr>--}}
                        {{--                        @endif--}}
                        </tbody>
                    </table>
                    {{--                    <x-paginate :model="$orders"/>--}}
                </div>
            </div>
        </div>
    </section>
@endsection
@section('modals')

    <x-modals.delete/>
    <x-modals.import route="/import/order"/>
@endsection

@section('js')
    <script src="{{asset('js/delete.js')}}"></script>
    <script src="{{asset('js/import.js')}}"></script>
@endsection
