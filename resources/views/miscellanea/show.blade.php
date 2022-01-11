@extends('layouts.app')

@section('title', "View Miscellanea")

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet" />
@endsection

@section('content')
    <x-wrappers.nav title="Show Misc" >
        <x-buttons.return :route="route('miscellaneous.index')" > Miscellaneous</x-buttons.return >
        <x-buttons.reports :route="route('miscellaneous.showPdf', $miscellaneou->id)" />
        <x-buttons.edit :route="route('miscellaneous.edit',$miscellaneou->id)" />
        <x-form.layout method="DELETE" class="d-sm-inline-block"
                       :id="'form'.$miscellaneou->id"
                       :action="route('miscellaneous.destroy', $miscellaneou->id)" >
            <x-buttons.delete formAttributes="data-id='{{$miscellaneou->id}}'" />
        </x-form.layout >
    </x-wrappers.nav >
    <x-handlers.alerts />
    <div class="row row-eq-height" >
        <x-miscellaneous.miscellanea-info :miscellaneou="$miscellaneou" />
        <x-miscellaneous.miscellanea-purchase :miscellaneou="$miscellaneou" />
    </div >

    <div class="row row-eq-height" >
        @if($miscellaneou->location()->exists())
            <div class="col-12 col-lg-8 mb-4" >
                <x-locations.location-modal :asset="$miscellaneou" />
            </div >
        @endif
        @if($miscellaneou->manufacturer()->exists())
            <div class="col-12 col-lg-4 mb-4" >
                <x-manufacturers.manufacturer-modal :asset="$miscellaneou" />
            </div >
        @endif
    </div >
    <div class="row row-eq-height" >
        <x-miscellaneous.miscellanea-log :miscellaneou="$miscellaneou" />
        <div class="col-12 col-lg-6 mb-4" >
            <x-comments.comment-layout :asset="$miscellaneou" />
        </div >
    </div >


@endsection

@section('modals')
    <x-modals.delete />
    <x-modals.status :model="$miscellaneou" :route="route('miscellaneous.status', $miscellaneou->id)"
                     title="miscellaneou" />
    <x-modals.add-comment :route="route('miscellaneous.comment' ,$miscellaneou->id)" :model="$miscellaneou"
                          title="miscellanea" />
    <x-modals.edit-comment :model="$miscellaneou" />
    <x-modals.delete-comment />

@endsection

@section('js')
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js" ></script >
    <script src="{{asset('js/delete.js')}}" ></script >
    <script src="{{asset('js/comment.js')}}" ></script >
    <script >
        $('#confirmBtn').click(function () {
            var form = '#' + 'form' + $('#miscellanea-id').val();
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
    </script >

@endsection
