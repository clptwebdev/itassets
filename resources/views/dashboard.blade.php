@extends('layouts.app')

@section('title', 'Dashboard')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')
@if(session('danger_message'))
    <div class="alert alert-danger"> {{ session('danger_message')}} </div>
@endif

@if(session('success_message'))
    <div class="alert alert-success"> {{ session('success_message')}} </div>
@endif

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>
@if($assets->count() != 0)

<x-admin.asset-info :transfers="$transfers" :archived="$archived" :assets="$assets" :accessories="$accessories" :components="$components" :consumables="$consumables" :miscellaneous="$miscellaneous"/>

<!-- Content Row -->
<div class="row row-eq-height mb-4">
    <div id="areaChart" class="col-12 col-xl-7">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Asset Value</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="valueBarChart"></canvas>
                </div>
            </div>
        </div>
        <div class="card shadow">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Asset Audits</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="assetLineChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div id="pieChart" class="col-12 col-xl-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Asset Allocation</h6>
            </div>
                <!-- Card Body -->
            <div class="card-body">
                <div class="pt-4 pb-2">
                    <canvas id="myPieChart" style="width: 400px; height: 400px;"></canvas>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Assets</h6>
            </div>

            <div class="card-body">
                @php
                        $total = $assets->count();
                        $deployable = 0;
                        foreach($statuses->where('deployable', '=', 1) as $status){
                            $deployable += $status->assets->count();
                        }
                @endphp
                @if($deployable!=0)
                <h4 class="small font-weight-bold">Asset Status<span class="float-right">{{round(($deployable / $total) * 100) ?? 0}}%</span></h4>
                @endif
                <div class="progress mb-1">
                    @foreach($statuses as $status)
                        @php
                        if($status->assets->count() != 0){
                            $percent = ($status->assets->count() / $total) * 100;
                        }else{
                            $percent = 0;
                        }
                        @endphp
                        <div class="progress-bar" role="progressbar"
                            style="background-color: {{$status->colour}}; width: {{ round($percent)}}%"
                            aria-valuenow="{{ $status->assets->count() }}" aria-valuemin="0"
                            aria-valuemax="100" title="{{$status->name}} - {{ $status->assets->count() }}"></div>
                    @endforeach

                    @if(!$percent === 0)
                    <div class="progress-bar bg-gray-200" role="progressbar" style="width: auto"
                        aria-valuenow="{{ round(100 - $percent) }}" aria-valuemin="0"
                        aria-valuemax="100" title="Unset"></div>
                    @endif
                </div>
                <div class="mb-4">
                    <small>
                        @foreach($statuses as $status)

                            <i class="fas fa-circle" style="color: {{ $status->colour}}"></i> {{$status->name}}

                        @endforeach
                    </small>
                </div>
                @php
                    $audits_due = 0; $audits_over = 0;
                @endphp

                <!-- Pending Requests Card Example -->
                @foreach($assets as $asset)
                    @if(\Carbon\Carbon::parse($asset->audit_date)->isPast())
                        @php($audits_over++)
                    @else
                        @php($age = Carbon\Carbon::now()->floatDiffInDays($asset->audit_date))
                        @if($age < 31)@php($audits_due++)@endif
                    @endif
                @endforeach
                @php($completed  = $assets->count() - ($audits_due + $audits_over))
                @if($completed != 0)
                <h4 class="small font-weight-bold">Audit Status <span class="float-right">{{ round(($completed / $assets->count()) * 100)}}% Complete</span></h4>
                @endif

                @if($audits_due !=0)
                <div class="progress mb-1">

                    <div class="progress-bar bg-danger" role="progressbar"
                        style="width:{{ round(($audits_over / $assets->count()) * 100)}}%"
                        aria-valuenow="{{ round(($audits_over / $assets->count()) * 100)}}" aria-valuemin="0"
                        aria-valuemax="100"></div>
                    <div class="progress-bar bg-warning" role="progressbar"
                        style="width: {{ round(($audits_due / $assets->count()) * 100)}}%"
                        aria-valuenow="{{ round(($audits_due / $assets->count()) * 100)}}" aria-valuemin="0"
                        aria-valuemax="100"></div>
                    <div class="progress-bar bg-success" role="progressbar"
                        style="width: {{ round(($completed / $assets->count()) * 100)}}%"
                        aria-valuenow="{{ round(($completed / $assets->count())* 100)}}" aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>
                @endif
                <small>
                    <i class="fas fa-circle text-danger" style></i> Overdue Audit
                    <i class="fas fa-circle text-warning" style></i> Audit Due
                    <i class="fas fa-circle text-success" style></i> Audit Completed
                </small>
            </div>
        </div>
    </div>
</div>

<div class="row row-eq-height mb-4">
    @foreach($locations as $location)
    <div class="col-md-12 col-xl-3 mb-3">
        <div class="card shadow bg-white" style="border-left: solid 5px {{$location->icon ?? '#666'}};">
            <div class="card-body">
                <div class="row pb-2">
                    <div class="col-12 col-md-10">
                        <span style="color:{{$location->icon}};">{{ $location->name}}</span>
                        <div class="text-gray-50 small">{{$location->address_1}}, @if($location->address_2 != ""){{ $location->address_2}},@endif {{ $location->city}}, {{ $location->postcode}} </div>
                    </div>
                    <div class="col-12 col-md-2" background>
                        <div class="border border-dark bg-white" style="height: 50px; width: 50px; border-radius: 50%; overflow: hidden; margin: auto;">
                        @if(isset($location->photo->path))
                            <img src="{{ asset($location->photo->path)}}" style="width: 100%; height: 100%; object-fit:cover; " alt="{{$location->name}}" title="{{ $location->name ?? 'Unnassigned'}}"/>
                        @else
                            {!! '<span class="d-flex justify-content-center align-items-center font-weight-bold bg-white" style="color:'.strtoupper($location->icon ?? '#666').'; height: 100%; width: 100%; font-size: 2.5rem">'
                                .strtoupper(substr($location->name ?? 'u', 0, 1)).'</span>' !!}
                        @endif
                        </div>
                    </div>
                </div>
                <div class="border-top border-light pt-4">
                </div>
                <div class="row no-gutters border-top border-light mt-4 pt-4">
                    <div class="col-12">
                        <table width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center"><span class="display-5 font-weight-bold btn btn-sm rounded text-white bg-lilac px-2" data-toggle="tooltip" data-placement="top" title="Assets"><i class="fas fa-fw fa-tablet-alt"></i></span></th>
                                    <th class="text-center"><span class="display-5 font-weight-bold btn btn-sm rounded text-white bg-lilac px-2" data-toggle="tooltip" data-placement="top" title="Accessories"><i class="fas fa-fw fa-keyboard"></i></span></th>
                                    <th class="text-center"><span class="display-5 font-weight-bold btn btn-sm rounded text-white bg-lilac px-2" data-toggle="tooltip" data-placement="top" title="Components"><i class="fas fa-fw fa-hdd"></i></span></th>
                                    <th class="text-center"><span class="display-5 font-weight-bold btn btn-sm rounded text-white bg-lilac px-2" data-toggle="tooltip" data-placement="top" title="Consumables"><i class="fas fa-fw fa-tint"></i></span></th>
                                    <th class="text-center"><span class="display-5 font-weight-bold btn btn-sm rounded text-white bg-lilac px-2" data-toggle="tooltip" data-placement="top" title="MIscellaneous"><i class="fas fa-fw fa-question"></i></span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">{{$location->asset->count() ?? "N/A"}}</td>
                                    <td class="text-center">{{$location->accessory->count() ?? "N/A"}}</td>
                                    <td class="text-center">{{$location->components->count() ?? "N/A"}}</td>
                                    <td class="text-center">{{$location->consumable->count() ?? "N/A"}}</td>
                                    <td class="text-center">{{$location->miscellanea->count() ?? "N/A"}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="row">
    <div class="col-6">
        <div class="card shadow mb-4 h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Categories</h6>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-center"><i class="fas fa-fw fa-tablet-alt"></i> <small
                                    class="d-none d-lg-inline-block">Assets</small></th>
                            <th class="text-center"><i class="fas fa-fw fa-keyboard"></i> <small
                                class="d-none d-lg-inline-block">Accessories</small></th>
                            <th class="text-center"><i class="fas fa-fw fa-hdd"></i> <small
                                class="d-none d-lg-inline-block">Components</small></th>
                            <th class="text-center"><i class="fas fa-fw fa-tint"></i> <small
                                class="d-none d-lg-inline-block">Consumables</small></th>
                            <th class="text-center"><i class="fas fa-fw fa-question"></i> <small
                                class="d-none d-lg-inline-block">Miscellanea</small></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="text-center"><a href="{{ route('status.index')}}" class="btn btn-green">View Statuses</a></td>
                        </tr>
                    </tfoot>
                    <tbody>
                    @foreach($category as $cat)
                        <tr>
                            <tr>
                                <td>{{ $cat->name }}</td>
                                <td class="text-center">{{$cat->assets->count()}}</td>
                                <td class="text-center">{{$cat->accessories->count()}}</td>
                                <td class="text-center">{{$cat->components->count()}}</td>
                                <td class="text-center">{{$cat->consumables->count()}}</td>
                                <td class="text-center">{{$cat->miscellanea->count()}}</td>
                            </tr>
                        </tr>
                    </tbody>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card shadow mb-4 h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Status</h6>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th class="text-center"><i class="fas fa-fw fa-tablet-alt"></i> <small
                                class="d-none d-lg-inline-block">Assets</small></th>
                        <th class="text-center"><i class="fas fa-fw fa-keyboard"></i> <small
                            class="d-none d-lg-inline-block">Accessories</small></th>
                        <th class="text-center"><i class="fas fa-fw fa-hdd"></i> <small
                            class="d-none d-lg-inline-block">Components</small></th>
                        <th class="text-center"><i class="fas fa-fw fa-tint"></i> <small
                            class="d-none d-lg-inline-block">Consumables</small></th>
                        <th class="text-center"><i class="fas fa-fw fa-question"></i> <small
                            class="d-none d-lg-inline-block">Miscellanea</small></th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <td colspan="6" class="text-center"><a href="{{ route('status.index')}}" class="btn btn-green">View Statuses</a></td>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($statuses as $status)
                        <tr>
                            <td>{{ $status->name }}</td>
                            <td class="text-center">{{$status->assets->count()}}</td>
                            <td class="text-center">{{$status->accessory->count()}}</td>
                            <td class="text-center">{{$status->components->count()}}</td>
                            <td class="text-center">{{$status->consumable->count()}}</td>
                            <td class="text-center">{{$status->miscellanea->count()}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('js')
<script src="{{ asset('js/chart.js') }}"></script>
<script src="{{ asset('js/demo/chart-bar-demo.js') }}"></script>
<script>
    $(document).ready(function () {
        showGraph();
        showValueGraph();
        showAssetGraph();
    });


        function showGraph() {
            $.ajax({
                url: 'chart/pie/locations',
                success: function (data) {
                    var as = JSON.parse(data);
                    var name = [];
                    var icon = [];
                    var assets = [];

                    for (var i in as) {
                        name.push(as[i].name);
                        icon.push(as[i].icon);
                        assets.push(as[i].asset);
                    }

                    var chartdata = {};

                    var ctx = document.getElementById("myPieChart");
                    var myPieChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: name,
                            datasets: [{
                                label: 'Asset Sources',
                                backgroundColor: icon,
                                borderColor: '#46d5f1',
                                hoverBackgroundColor: '#CCCCCC',
                                hoverBorderColor: '#666666',
                                data: assets
                            }],
                        },

                    });
                    ctx.height = 500;

                },
                error: function () {
                    console.log('Eror');
                },
            });
        }

        function showValueGraph() {
            $.ajax({
                url: 'chart/asset/values',
                success: function (data) {
                    var as = JSON.parse(data);
                    var dataSets = [];
                    for (var i in as) {
                        var data = [];
                        for (var d in as[i]['years']) {
                            data.push(as[i]['years'][d]);
                        }
                        dataSets.push({
                            label: as[i]['name'],
                            backgroundColor: as[i]['icon'],
                            borderColor: '#46d5f1',
                            hoverBackgroundColor: '#CCCCCC',
                            hoverBorderColor: '#666666',
                            data: data,
                        });
                    }

                    var ctx = document.getElementById("valueBarChart");
                    var myPieChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: [2021, 2022, 2023, 2024],
                            datasets: dataSets,
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    stacked: true,
                                },
                                y: {
                                    stacked: true
                                }
                            }
                        }
                    });
                    ctx.height = 500;
                },
                error: function () {
                    console.log('Eror');
                },
            });
        }

        function showAssetGraph() {
            $.ajax({
                url: 'chart/asset/audits',
                success: function (data) {
                    var as = JSON.parse(data);
                    var dataSets = [];
                    for (var i in as) {
                        dataSets.push({
                            label: as[i]['name'],
                            backgroundColor: as[i]['icon'],
                            borderColor: as[i]['icon'],
                            hoverBackgroundColor: '#CCCCCC',
                            hoverBorderColor: '#666666',
                            data: [as[i]['past'], as[i]['month'], as[i]['quarter'], as[i]['half']],
                        });
                    }

                    var ctx = document.getElementById("assetLineChart");
                    var myPieChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['Overdue', 'Less than a Month', 'Due in 1-3 Months', 'Due in 4-6 Months'],
                            datasets: dataSets,
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    stacked: true,
                                },
                                y: {
                                    stacked: true
                                }
                            }
                        }
                    });

                    ctx.height = 500;
                },
                error: function () {
                    console.log('Eror');
                },
            });
        }

    </script>

    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable();
        });
    </script>
@endsection
