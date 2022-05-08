@extends('layouts.app')

@section('title', "View Consumable")

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">View Consumables</h1>
        <div>
            <a href="{{ route('consumables.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                    class="fas fa-chevron-left fa-sm text-white-50"></i> Back</a>
            @can('generatePDF', $consumable)
                <a href="{{ route('consumables.showPdf', $consumable->id)}}"
                   class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm loading"><i
                        class="fas fa-file-pdf fa-sm text-white-50"></i> Generate Report</a>
            @endcan
            @can('update', $consumable)
                <a href="{{ route('consumables.edit', $consumable->id)}}"
                   class="d-none d-sm-inline-block btn btn-sm btn-yellow shadow-sm"><i
                        class="fas fa-edit fa-sm text-white-50"></i> Edit</a>
            @endcan
            <form class="d-inline-block id=" form{{$consumable->id}}"
                                                                    action="{{ route('consumables.destroy', $consumable->id) }}
                                                                    "
                                                                    method="POST">
            @csrf
            @method('DELETE')
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-coral shadow-sm deleteBtn"
               data-id="{{$consumable->id}}"><i class="fas fa-trash fa-sm text-white-50"></i> Delete</a>
            </form>
        </div>
    </div>

    <x-handlers.alerts/>

    <div class="row row-eq-height">
        <x-consumables.consumable-info :consumable="$consumable"/>
        <x-consumables.consumable-purchase :consumable="$consumable"/>
    </div>

    <div class="row row-eq-height">
        @if($consumable->location()->exists())
            <div class="col-12 col-lg-8 mb-4">
                <x-locations.location-modal :asset="$consumable"/>
            </div>
        @endif
        @if($consumable->manufacturer()->exists())
            <div class="col-12 col-lg-4 mb-4">
                <x-manufacturers.manufacturer-modal :asset="$consumable"/>
            </div>
        @endif
    </div>
    <div class="row row-eq-height">
        <x-consumables.consumable-log :consumable="$consumable"/>
        <div class="col-12 col-lg-6 mb-4">
            <x-comments.comment-layout :asset="$consumable"/>
        </div>
    </div>


@endsection

@section('modals')
    <x-modals.delete :archive="false"/>
    <x-modals.status :model="$consumable" :route="route('consumables.status', $consumable->id)" title="consumables"/>
    <x-modals.add-comment :route="route('consumables.comment' ,$consumable->id)" :model="$consumable"
                          title="consumables"/>
    <x-modals.edit-comment :model="$consumable"/>
    <x-modals.delete-comment/>
@endsection

@section('js')
    <script src="{{asset('js/delete.js')}}"></script>
    <script src="{{asset('js/comment.js')}}"></script>


@endsection
