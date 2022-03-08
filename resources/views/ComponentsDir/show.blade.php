@extends('layouts.app')

@section('title', 'View Component')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')

    <x-wrappers.nav title="Show Components">
        @can('viewAll' , \App\Models\Component::class)
            <x-buttons.return :route="route('components.index')"> Components</x-buttons.return>
        @endcan
        @can('generateComponentPDF' ,$data)
            <x-buttons.reports :route="route('components.showPdf', $data->id)"/>
        @endcan
        @can('update', $data)
            <x-buttons.edit :route="route('components.edit',$data->id)"/>
        @endcan
        @can('delete', \App\Models\Component::class)
            <x-form.layout method="DELETE" class="d-sm-inline-block" :id="'form'.$data->id"
                           :action="route('components.destroy', $data->id)">
                <x-buttons.delete formAttributes="data-id='{{$data->id}}'"/>
            </x-form.layout>
        @endcan
    </x-wrappers.nav>

    <x-handlers.alerts/>

    <section class="m-auto">
        <p class="mb-4">Information regarding {{ $data->name }} including the location and any comments made by
                        staff. </p>

        <div class="row row-eq-height">
            <x-components.component-info :component="$data"/>
            <x-components.component-purchase :component="$data"/>
        </div>

        <div class="row row-eq-height">
            <div class="col-12 col-lg-8 mb-4">
                @if($data->location()->exists())
                    <x-locations.location-modal :asset="$data"/>
                @endif
            </div>

            <div class="col-12 col-lg-4 mb-4">
                @if($data->manufacturer()->exists())
                    <x-manufacturers.manufacturer-modal :asset="$data"/>
                @endif
            </div>
        </div>

        <div class="row row-eq-height">
            <x-components.component-log :component="$data"/>
            <div class="col-12 col-lg-6 mb-4">
                <x-comments.comment-layout :asset="$data"/>
            </div>
        </div>

    </section>

@endsection

@section('modals')
    <x-modals.delete/>
    <x-modals.status :model="$data" :route="route('component.status', $data->id)" title="component"/>
    <x-modals.add-comment :route="route('component.comment' ,$data->id)" :model="$data" title="component"/>
    <x-modals.edit-comment :model="$data"/>
    <x-modals.delete-comment/>

@endsection

@section('js')
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('js/delete.js')}}"></script>
    <script src="{{asset('js/comment.js')}}"></script>
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
