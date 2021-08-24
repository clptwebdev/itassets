@extends('layouts.app')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')
@if(session('danger_message'))
    <div class="alert alert-danger"> {{ session('danger_message')}} </div>
@endif
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>


    @if(session('danger_message'))
        <div class="alert alert-danger"> {{ session('danger_message')}} </div>
    @endif

    @if(session('success_message'))
        <div class="alert alert-success"> {{ session('success_message')}} </div>
    @endif
<x-admin.asset-info/>
    <!-- Content Row -->
    <div id="coolImage"></div>
    <div class="row row-eq-height mb-4">

        <!-- Area Chart -->
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
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                             aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Dropdown Header:</div>
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="valueBarChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="card shadow ">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                             aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Dropdown Header:</div>
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
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
                <!-- Card Header - Dropdown -->
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Asset Allocation</h6>

                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                             aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Dropdown Header:</div>
                            <a class="dropdown-item" href="#">Action 1</a>
                            <a class="dropdown-item" href="#">Action 2</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </div>
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
                        foreach(\App\Models\Status::where('deployable', '=', 1)->get() as $status){
                            $deployable += $status->assets->count();
                        }
                    @endphp
                    @if(!$deployable===0)
                        <h4 class="small font-weight-bold">Asset Status<span class="float-right">{{round(($deployable / $total) * 100) ?? 0}}%</span>
                        </h4>@endif
                    <div class="progress mb-1">
                        @foreach(\App\Models\Status::all() as $status)
                            @php
                                $count = $status->assets->count();
                             if(!$count ===0){
                                $percent = ($count / $total) * 100;
                             }else{
                             	$percent = 0;
                             }
                            @endphp
                            <div class="progress-bar" role="progressbar"
                                 style="background-color: {{$status->colour}}; width: {{ round($percent)}}%"
                                 aria-valuenow="{{ $count }}" aria-valuemin="0"
                                 aria-valuemax="100" title="{{$status->name}} - {{ $count }}"></div>
                        @endforeach
                        @if(!$percent === 0)
                        <div class="progress-bar bg-gray-200" role="progressbar" style="width: auto"
                             aria-valuenow="{{ round(100 - $percent) }}" aria-valuemin="0"
                             aria-valuemax="100" title="Unset"></div>
                            @endif
                    </div>
                    <div class="mb-4">
                        <small>
                            @foreach(\App\Models\Status::all() as $status)

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
                    @if(!$completed === 0)
                    <h4 class="small font-weight-bold">Audit Status <span class="float-right">{{ round(($completed / $assets->count()) * 100)}}% Complete</span>
                        @endif
                    </h4>
                        @if(!$audits_due ===0)
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
                        <i class="fas fa-circle text-danger" style></i> Overdue Audit <i
                            class="fas fa-circle text-warning" style></i> Audit Due <i
                            class="fas fa-circle text-success" style></i> Audit Completed</small>
                </div>
            </div>
        </div>
    </div>
<!-- Content Row -->
<div class="row">

    <!-- Content Column -->
    <div class="col-xl-6 mb-4">
        <!-- Project Card Example -->


        <!-- Color System -->
        <div class="row row-eq-height">
            @foreach($locations as $location)
            <div class="col-md-12 col-xl-6 mb-4 h-100">
                <div class="card shadow" style="background-color: {{$location->icon}}; color: #FFF">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-9">
                                {{ $location->name}}
                                <div class="text-white-50 small">{{$location->icon}}</div>
                            </div>
                            <div class="col-12 col-md-3" background>
                                @if(isset($location->photo->path))
                                    '<img src="{{ asset($location->photo->path)}}" height="50px" alt="{{$location->name}}" title="{{ $location->name ?? 'Unnassigned'}}"/>'
                                @else
                                    {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($location->icon ?? '#666').'">'
                                        .strtoupper(substr($location->name ?? 'u', 0, 1)).'</span>' !!}
                                @endif
>>>>>>> main
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

        <div class="col-lg-6 mb-4">

            <!-- Illustrations -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Categories</h6>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-center"><i class="fas fa-fw fa-tablet-alt"></i> <span
                                    class="d-none d-lg-inline-block">Assets</span></th>
                            <th class="text-center">Components</th>
                            <th class="text-center">Accessories</th>
                            <th class="text-center">Consumables</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <td colspan="5" class="text-center">View Categories</td>
                        </tr>
                        </tfoot>
                        <tbody>
                        @php($category = \App\Models\Category::all())
                        @foreach($category as $cat)
                            <tr>
                                <td>{{ $cat->name }}</td>
                                <td class="text-center">{{$cat->assets->count()}}</td>
                                <td class="text-center">243</td>
                                <td class="text-center">1021</td>
                                <td class="text-center">5</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>

    </div>

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


    function showGraph()
    {
        $.ajax({
        url: 'chart/pie/locations',
        success: function(data) {
            var as = JSON.parse(data);
            var name = [];
            var icon = [];
            var assets = [];

            for (var i in as) {
                name.push(as[i].name);
                icon.push(as[i].icon);
                assets.push(as[i].asset);
            }

            var chartdata = {

            };

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
        error: function(){
            console.log('Eror');
        },
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
