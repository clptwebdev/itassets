@extends('layouts.app')

@section('title', "View Asset {$asset->asset_tag}")

@section('css')

@endsection


@section('content')
    <x-wrappers.nav title="View Asset">
        <x-buttons.return :route="route('assets.index')">Assets</x-buttons.return>
        @can('dispose', $asset)
            <x-buttons.dispose
                formRequirements="data-model-id='{{$asset->id}}' data-model-name='{{$asset->name ?? 'No name' }}'"/>
        @endcan
        @can('transfer', $asset)
            <x-buttons.transfer
                formRequirements="data-model-id='{{$asset->id}}' data-location-from='{{$asset->location->name ?? 'Unallocated' }}' data-location-id='{{ $asset->location_id }}'"/>
        @endcan
        @can('generatePDF', \App\Models\Asset::class)
            <x-buttons.reports :route="route('asset.showPdf', $asset->id)"/>
        @endcan
        @can('update', $asset)
            <x-buttons.edit :route="route('assets.edit', $asset->id)"/>
        @endcan
        @can('delete', $asset)
            <x-form.layout method="DELETE" class="d-inline-block" :id="'form'.$asset->id"
                           :action="route('assets.destroy', $asset->id)">
                <x-buttons.delete formAttributes="data-id='{{$asset->id}}'"/>
            </x-form.layout>
        @endcan
    </x-wrappers.nav>
    <x-handlers.alerts/>

    <div class="row row-eq-height">
        <x-assets.asset-modal :asset="$asset"/>
        <x-assets.asset-purchase :asset="$asset"/>
    </div>

    <div class="row row-eq-height">
        <div class="col-12 col-lg-8 mb-4">
            @if($asset->location()->exists())
                <x-locations.location-modal :asset="$asset"/>
            @endif
        </div>

        <div class="col-12 col-lg-4 mb-4">
            @if($asset->model()->exists() && $asset->model->manufacturer()->exists())
                <x-manufacturers.manufacturer-modal :asset="$asset->model"/>
            @endif
        </div>

    </div>
    <div class="row row-eq-height">
        <x-assets.asset-log :asset="$asset"/>
        <div class="col-12 col-lg-6 mb-4">
            <x-comments.comment-layout :asset="$asset"/>
        </div>
    </div>


@endsection

@section('modals')
    <x-modals.dispose model="asset"/>
    <x-modals.transfer :models="$locations" model="asset" :tag="$asset->asset_tag"/>
    <x-modals.delete/>
    <x-modals.status :model="$asset" :route="route('change.status', $asset->id)" title="asset"/>
    <x-modals.add-comment :route="route('asset.comment' ,$asset->id)" :model="$asset" title="asset"/>
    <x-modals.edit-comment :model="$asset"/>
    <x-modals.delete-comment/>
@endsection

@section('js')
    <script src="{{asset('js/dispose.js')}}"></script>
    <script src="{{asset('js/transfer.js')}}"></script>
    <script src="{{asset('js/delete.js')}}"></script>
    <script src="{{asset('js/comment.js')}}"></script>

@endsection
