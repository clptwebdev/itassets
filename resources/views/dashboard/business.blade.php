@extends('layouts.app')

@section('title', 'Dashboard')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')
    <!-- session messages -->
    <x-handlers.alerts/>

    <!-- Page Heading -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard - Business Overview</h1>
        <div>
            <div class="dropdown d-inline">
                <a class="btn btn-sm btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    Download Trust Report
                </a>

                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    @foreach($locations as $location)
                        <li><a class="dropdown-item"
                               href="{{route('business.location.export', $location->id)}}">{{$location->name}}</a></li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn btn-sm btn-blue">Reports</button>
            <a href="{{ route('cache.clear')}}" class="btn btn-sm btn-grey"><i class="fas fa-sync-alt"></i> Clear Report
                                                                                                            Cache</a>
        </div>
    </div>

    @if(auth()->user()->role_id != 0)
        <!-- Asset stats -->
        <!-- Content Row -->
        <div class=" p-2 mb-1 ">
            <!-- Total-->
            <div class="row rounded p-2 pb-lg-4" style="background-color: #EEE">

                <div class="col-12 col-sm-8 col-lg-2 mb-4 mb-lg-0 order-3 order-lg-1">
                    <div class="card border-left-lilac shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-lilac text-uppercase mb-1">
                                        Property {{Illuminate\Support\Facades\Cache::get("property-L7-total")}}
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <small>Total: <span id="total_count" class="countup"></span></small><br>
                                        <span id="total_cost" class=""></span><br>
                                        <small class="text-coral">(<span id="total_dep" class=""></span>)*</small><br>
                                        <span class="text-xs">*calculated depreciation</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-school fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                                </div>
                            </div>
                        </div>
                        <div class="stats_loading d-flex justify-content-center align-items-center"
                             style="position: absolute; z-index: 2; width: 100%; height: 100%; top: 0; left: 0; background-color: rgba(255,255,255,0.8);">
                            <div class="spinner-border text-secondary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Earnings (Monthly) Card Example -->
                <div class="col-12 col-sm-6 col-lg-2 mb-4 mb-lg-0 order-1 order-lg-2">
                    <div class="card border-left-coral shadow h-100 py-2 postion-relative">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-1">
                                    <div class="text-xs font-weight-bold text-coral text-uppercase mb-1">
                                        Assets Under Construction
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <small>Total: <span id="auc_count" class="countup"></span></small><br>
                                        <span id="auc_cost" class=""></span><br>
                                        <small class="text-coral">(<span id="auc_dep" class="countup"></span>)*</small><br>
                                        <span class="text-xs">*calculated depreciation</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-hammer fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                                </div>
                            </div>
                        </div>
                        <div class="stats_loading d-flex justify-content-center align-items-center"
                             style="position: absolute; z-index: 2; width: 100%; height: 100%; top: 0; left: 0; background-color: rgba(255,255,255,0.8);">
                            <div class="spinner-border text-secondary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-2 mb-4 mb-lg-0 order-2 order-lg-3">
                    <div class="card border-left-blue shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-1">
                                    <div class="text-xs font-weight-bold text-blue text-uppercase mb-1">
                                        FFE
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <small>Total: <span id="ffe_count"></span></small><br>
                                        <span id="ffe_cost"></span><br>
                                        <small class="text-coral">(<span id="ffe_dep"></span>)</small><br>
                                        <span class="text-xs">*calculated depreciation</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chair fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                                </div>
                            </div>
                        </div>
                        <div class="stats_loading d-flex justify-content-center align-items-center"
                             style="position: absolute; z-index: 2; width: 100%; height: 100%; top: 0; left: 0; background-color: rgba(255,255,255,0.8);">
                            <div class="spinner-border text-secondary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-2 mb-4 mb-lg-0 order-2 order-lg-3">
                    <div class="card border-left-blue shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-1">
                                    <div class="text-xs font-weight-bold text-blue text-uppercase mb-1">
                                        Plant & Machinery
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <small>Total: <span id="machinery_count"></span></small><br>
                                        <span id="machinery_cost"></span><br>
                                        <small class="text-coral">(<span id="machinery_dep"></span>)</small><br>
                                        <span class="text-xs">*calculated depreciation</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-tractor fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                                </div>
                            </div>
                        </div>
                        <div class="stats_loading d-flex justify-content-center align-items-center"
                             style="position: absolute; z-index: 2; width: 100%; height: 100%; top: 0; left: 0; background-color: rgba(255,255,255,0.8);">
                            <div class="spinner-border text-secondary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-2 mb-4 mb-lg-0 order-2 order-lg-3">
                    <div class="card border-left-blue shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-1">
                                    <div class="text-xs font-weight-bold text-blue text-uppercase mb-1">
                                        Motor Vehicles
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <small>Total: <span id="vehicle_count"></span></small><br>
                                        <span id="vehicle_cost"></span><br>
                                        <small class="text-coral">(<span id="vehicle_dep"
                                                                          class="countup"></span>)</small><br>
                                        <span class="text-xs">*calculated depreciation</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-bus fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                                </div>
                            </div>
                        </div>
                        <div class="stats_loading d-flex justify-content-center align-items-center"
                             style="position: absolute; z-index: 2; width: 100%; height: 100%; top: 0; left: 0; background-color: rgba(255,255,255,0.8);">
                            <div class="spinner-border text-secondary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-2 mb-4 mb-lg-0 order-2 order-lg-3">
                    <div class="card border-left-blue shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-1">
                                    <div class="text-xs font-weight-bold text-blue text-uppercase mb-1">
                                        Computer Equipment
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <small>Total: <span id="computer_count"></span></small><br>
                                        <span id="computer_cost"></span><br>
                                        <small class="text-coral">(<span id="computer_dep"></span>)</small><br>
                                        <span class="text-xs">*calculated depreciation</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-keyboard fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                                </div>
                            </div>
                        </div>
                        <div class="stats_loading d-flex justify-content-center align-items-center"
                             style="position: absolute; z-index: 2; width: 100%; height: 100%; top: 0; left: 0; background-color: rgba(255,255,255,0.8);">
                            <div class="spinner-border text-secondary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <x-categories_status_info :statuses="$statuses" :category="$category"/> --}}

        <div class="row m-2">
            <div class="col-12  mb-4">
                <div class="card shadow h-100 p-4">
                    <div id="all_expenditure_chart" class="chart"></div>
                </div>
            </div>
            {{-- Expenditure --}}
            <div class="col-12 col-md-6 mb-3 ">
                <div class="card shadow h-100 p-4 ">
                    <div id="chart" class="chart"></div>
                </div>
            </div>
            {{-- Depreication Information --}}

            <div class="col-12 col-md-6 mb-3 ">
                <div class="card shadow h-100 p-4">
                    <div id="dep_chart" class="chart"></div>
                </div>
            </div>
        </div>
    @else
        <x-admin.request-access/>
    @endif

@endsection

@section('js')

    <script type="text/javascript">

        const propertyCount = document.querySelector('#total_count');
        const propertyCost = document.querySelector('#total_cost');
        const propertyDep = document.querySelector('#total_dep');
        const aucCount = document.querySelector('#auc_count');
        const aucCost = document.querySelector('#auc_cost');
        const aucDep = document.querySelector('#auc_dep');
        const ffeCount = document.querySelector('#ffe_count');
        const ffeCost = document.querySelector('#ffe_cost');
        const ffeDep = document.querySelector('#ffe_dep');
        const machineryCount = document.querySelector('#machinery_count');
        const machineryCost = document.querySelector('#machinery_cost');
        const machineryDep = document.querySelector('#machinery_dep');
        const vehicleCount = document.querySelector('#vehicle_count');
        const vehicleCost = document.querySelector('#vehicle_cost');
        const vehicleDep = document.querySelector('#vehicle_dep');
        const computerCount = document.querySelector('#computer_count');
        const computerCost = document.querySelector('#computer_cost');
        const computerDep = document.querySelector('#computer_dep');

        const loader = document.querySelectorAll('.stats_loading');

        const currencyOptions = {
            style: 'currency',
            currency: 'GBP',
        };

        const xhttp = new XMLHttpRequest();

        xhttp.onload = function () {
            loader.forEach(function (el) {
                el.classList.remove('d-flex');
                el.classList.add('d-none');
            });
            //Fetch the return JSON Object
            const obj = JSON.parse(xhttp.responseText);
            console.log(obj);
            propertyCount.innerHTML = obj.property.count;
            propertyCost.innerHTML = new Intl.NumberFormat('en-GB', currencyOptions).format(obj.property.cost);
            propertyDep.innerHTML = new Intl.NumberFormat('en-GB', currencyOptions).format(obj.property.cost);
            //AUC
            aucCount.innerHTML = obj.auc.count;
            aucCost.innerHTML = new Intl.NumberFormat('en-GB', currencyOptions).format(obj.auc.cost);
            aucDep.innerHTML = new Intl.NumberFormat('en-GB', currencyOptions).format(obj.auc.dep);
            //FFE
            ffeCount.innerHTML = obj.ffe.count;
            ffeCost.innerHTML = new Intl.NumberFormat('en-GB', currencyOptions).format(obj.ffe.cost);
            ffeDep.innerHTML = new Intl.NumberFormat('en-GB', currencyOptions).format(obj.ffe.dep);
            //FFE
            machineryCount.innerHTML = obj.machinery.count;
            machineryCost.innerHTML = new Intl.NumberFormat('en-GB', currencyOptions).format(obj.machinery.cost);
            machineryDep.innerHTML = new Intl.NumberFormat('en-GB', currencyOptions).format(obj.machinery.dep);
            //Vehicles
            vehicleCount.innerHTML = obj.vehicles.count;
            vehicleCost.innerHTML = new Intl.NumberFormat('en-GB', currencyOptions).format(obj.vehicles.cost);
            vehicleDep.innerHTML = new Intl.NumberFormat('en-GB', currencyOptions).format(obj.vehicles.dep);
            //Asset
            computerCount.innerHTML = obj.asset.count;
            computerCost.innerHTML = new Intl.NumberFormat('en-GB', currencyOptions).format(obj.asset.cost + obj.accessories.count);
            computerDep.innerHTML = new Intl.NumberFormat('en-GB', currencyOptions).format(obj.asset.dep + obj.accessories.dep)

        }

        xhttp.open("GET", "/business/statistics");
        xhttp.send();

    </script>

    <!-- Charting library -->
    <script src="https://unpkg.com/chart.js@2.9.3/dist/Chart.min.js"></script>
    <!-- Chartisan -->
    <script src="https://unpkg.com/@chartisan/chartjs@^2.1.0/dist/chartisan_chartjs.umd.js"></script>
    <!-- Your application script -->
    <script>

        const device = legend = (screen.width < 768) ? false : true; //when viewport will be under 575px

        const expenditure = new Chartisan({
            el: '#all_expenditure_chart',
            url: `@chart('all_expenditure_chart')`,
            // You can also pass the data manually instead of the url:
            // data: { ... }
            hooks: new ChartisanHooks()
                .datasets([
                    {type: 'line', fill: false}
                ])
                .responsive()
                .title('Expenditure for Schools')
                .responsive()
                .legend(device)
                .displayAxes(device)
                .custom(function ({data, merge, server}) {
                    //---> loop through extra from server
                    for (let i = 0; i < server.datasets.length; i++) {
                        const extras = server.datasets[i].extra; // extra object
                        for (const [key, value] of Object.entries(extras)) { // loop through extras
                            data.data.datasets[i][key] = value; // add extras to data
                        }

                    }
                    return merge(data, {
                        options: {
                            aspectRatio: 1,
                        }
                    })
                })
        });

        const chart = new Chartisan({
            el: '#dep_chart',
            url: `@chart('depreciation_chart')`,
            // You can also pass the data manually instead of the url:
            // data: { ... }
            hooks: new ChartisanHooks()
                .datasets([{type: 'line', fill: false}])
                .responsive()
                .colors(['#F99'])
                .title('Asset Depreciation')
                .legend(device)
                .displayAxes(device)
        })

        const dep_chart = new Chartisan({
            el: '#chart',
            url: `@chart('total_expenditure')`,
            // You can also pass the data manually instead of the url:
            // data: { ... }
            hooks: new ChartisanHooks()
                .datasets('bar')
                .colors(['#b087bc', '#474775'])
                .title('CLPT Expenditure')
                .legend(device)
                .responsive()
                .displayAxes(device)
        })
    </script>

@endsection
