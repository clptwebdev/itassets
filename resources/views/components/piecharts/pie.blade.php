<!-- Content Row -->
<div class="row row-eq-height mb-4">
    <div id="areaChart" class="col-12 col-xl-7">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Asset Value</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
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
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
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
                    <h4 class="small font-weight-bold">Asset Status<span class="float-right">{{round(($deployable / $total) * 100) ?? 0}}%</span>
                    </h4>
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
                             aria-valuenow="{{ $status->assets->count() }}" aria-valuemin="0" aria-valuemax="100"
                             title="{{$status->name}} - {{ $status->assets->count() }}"></div>
                    @endforeach

                    @if(!$percent === 0)
                        <div class="progress-bar bg-gray-200" role="progressbar" style="width: auto"
                             aria-valuenow="{{ round(100 - $percent) }}" aria-valuemin="0" aria-valuemax="100"
                             title="Unset"></div>
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
                    <h4 class="small font-weight-bold">Audit Status <span class="float-right">{{ round(($completed / $assets->count()) * 100)}}% Complete</span>
                    </h4>
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
