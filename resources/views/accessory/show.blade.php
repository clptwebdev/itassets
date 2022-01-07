@extends('layouts.app')

@section('title', 'View '.$accessory->name)

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')
    <x-wrappers.nav title="View Accessory">
        <x-buttons.return :route="route('accessories.index')">Accessories</x-buttons.return>
        @can('dispose', $accessory)
            <x-buttons.dispose
                formRequirements="data-model-id='{{$accessory->id}}' data-model-name='{{$accessory->name ?? 'No name' }}'"/>
        @endcan
        @can('transfer', $accessory)
            <x-buttons.transfer
                formRequirements="data-model-id='{{$accessory->id}}' data-location-from='{{$accessory->location->name ?? 'Unallocated' }}' data-location-id='{{ $accessory->location_id }}'"/>
        @endcan
        @can('generatePDF', \App\Models\Accessory::class)
            <x-buttons.reports :route="route('accessories.showPdf', $accessory->id)"/>
        @endcan
        <x-buttons.edit :route="route('accessories.edit', $accessory->id)"/>

        @can('delete', $accessory)
            <x-form.layout method="DELETE" class="d-sm-inline-block"
                           :id="'form'.$accessory->id"
                           :action="route('accessories.destroy', $accessory->id)">
                <x-buttons.delete formAttributes="data-id='{{$accessory->id}}'"/>
            </x-form.layout>
        @endcan
    </x-wrappers.nav>
    <x-handlers.alerts/>
    <section class="m-auto">
        <p class="mb-4">Information regarding {{ $accessory->name }} including the location and any comments made by
            staff. </p>

        <div class="row row-eq-height">
            <x-accessories.accessory-info :accessory="$accessory"/>
            <x-accessories.accessory-purchase :accessory="$accessory"/>
        </div>

        <div class="row row-eq-height">
            <div class="col-12 col-lg-8 mb-4">
                <x-locations.location-modal :asset="$accessory"/>
            </div>
            <div class="col-12 col-lg-4 mb-4">
                <x-manufacturers.manufacturer-modal :asset="$accessory"/>
            </div>
        </div>

        <div class="row row-eq-height">
            <x-accessories.accessory-log :accessory="$accessory"/>
            <div class="col-12 col-lg-6 mb-4">
                <x-comments.comment-layout :asset="$accessory"/>
            </div>
        </div>

    </section>

@endsection

@section('modals')
    <x-modals.dispose model="accessory"/>
    <x-modals.transfer :models="$locations" model="accessory" :tag="$accessory->asset_tag"/>
    <x-modals.delete/>
    <x-modals.status :model="$accessory" :route="route('accessories.status',$accessory->id)"/>
    <x-modals.add-comment :route="route('accessories.comment', $accessory->id)" :model="$accessory"/>
    <x-modals.edit-comment :model="$accessory"/>
    <x-modals.delete-comment/>
@endsection

@section('js')
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('js/transfer.js')}}"></script>
    <script src="{{asset('js/dispose.js')}}"></script>
    <script src="{{asset('js/comment.js')}}"></script>
    <script src="{{asset('js/delete.js')}}"></script>
    <script>
        $('#confirmBtn').click(function () {
            var form = '#' + 'form' + $('#location-id').val();
            $(form).submit();
        });

        $(document).ready(function () {
            $('#comments').DataTable({
                "autoWidth": false,
                "pageLength": 10,
                "searching": false,
                "bLengthChange": false,
                "columnDefs": [{
                    "targets": [1],
                    "orderable": false
                }],
                "order": [[0, "desc"]],
            });
        });

    </script>

@endsection
