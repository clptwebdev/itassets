@extends('layouts.app')

@section('title', 'Settings')

@section('css')
@endsection

@section('content')


    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Settings</h1>
        <div>
            {{--            nav--}}
        </div>
    </div>
    <section>
        <p class="mb-4">Below are the different Settings for the management system. Each has
            different options.</p>
        @if(session('danger_message'))
            <div class="alert alert-danger"> {!!session('danger_message')!!} </div>
        @endif

        @if(session('success_message'))
            <div class="alert alert-success"> {!! session('success_message')!!} </div>
        @endif
        <a data-toggle="modal" data-target="#exportModal" class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm"> Exports Items Here</a>

    </section>
@endsection
@section('modals')
    <x-components.export :users="$users" :assets="$assets" :components="$components" :accessories="$accessories"
                         :miscellaneous="$miscellaneous" :locations="$locations" :categories="$categories" :statuses="$statuses"
    :assetModel="$assetModel"/>

@endsection
@section('js')
    <script>
        // import
        $('#export').click(function () {
//showModal
            $('#exportModal').modal('show')
        });
    </script>
@endsection
