@extends('layouts.app')

@section('title', 'Dashboard')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')
    <!-- session messages -->
    <x-handlers.alerts/>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    @if($assets->count() != 0)
        <!-- Asset stats -->
        <x-admin.asset-info :transfers=$transfers :archived=$archived :assets=$assets :accessories=$accessories
                            :components=$components :consumables=$consumables :miscellaneous=$miscellaneous
                            :requests=$requests/>
        <x-categories_status_info :statuses="$statuses" :category="$category"/>
    @elseif(auth()->user()->role_id == 0)
                <x-admin.request-access/>
    @endif
@endsection
