@extends('layouts.app')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
</div>

<x-admin.asset-info/>

<!-- Content Row -->

<div class="row">

    <!-- Area Chart -->
    <div class="col-xl-7 col-lg-6">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
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
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pie Chart -->
    <div class="col-xl-5 col-lg-6">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
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
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="myPieChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-primary"></i> Heath Park
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-success"></i> Moseley Park
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle" style></i> Coppice
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">

    <!-- Content Column -->
    <div class="col-lg-6 mb-4">

        <!-- Project Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Assets</h6>
            </div>
            <div class="card-body">
                <h4 class="small font-weight-bold">Asset Status<span class="float-right">85%</span></h4>
                <div class="progress mb-1">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 75%" aria-valuenow="20" aria-valuemin="0"
                        aria-valuemax="100"></div>
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 10%" aria-valuenow="35" aria-valuemin="0"
                        aria-valuemax="100"></div>
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 15%" aria-valuenow="45" aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>
                <div class="mb-4">
                <small><i class="fas fa-circle text-success" style></i> Deployed <i class="fas fa-circle text-secondary" style></i> Deployable <i class="fas fa-circle text-danger" style></i> Out of Service</small>
                </div>
                
                <h4 class="small font-weight-bold">Audit Status <span class="float-right">45% Complete</span></h4>
                <div class="progress mb-1">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0"
                        aria-valuemax="100"></div>
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 35%" aria-valuenow="35" aria-valuemin="0"
                        aria-valuemax="100"></div>
                    <div class="progress-bar bg-success" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>
                <small>
                <i class="fas fa-circle text-danger" style></i> Overdue Audit <i class="fas fa-circle text-warning" style></i> Audit Due <i
                            class="fas fa-circle text-success" style></i> Audit Completed</small>
            </div>
        </div>

        <!-- Color System -->
        <div class="row">
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow" style="background-color: #001b54; color: #FFF">
                    <div class="card-body">
                        Heath Park
                        <div class="text-white-50 small">#001b54</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow" style="background-color: #72277f; color: #FFF">
                    <div class="card-body">
                        Moseley Park
                        <div class="text-white-50 small">#72277f</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow" style="background-color: #774b96; color: #FFF">
                    <div class="card-body">
                        Coppice School
                        <div class="text-white-50 small">#774b96</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card bg-warning text-white shadow">
                    <div class="card-body">
                        Warning
                        <div class="text-white-50 small">#f6c23e</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                        Danger
                        <div class="text-white-50 small">#e74a3b</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card bg-secondary text-white shadow">
                    <div class="card-body">
                        Secondary
                        <div class="text-white-50 small">#858796</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card bg-light text-black shadow">
                    <div class="card-body">
                        Light
                        <div class="text-black-50 small">#f8f9fc</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card bg-dark text-white shadow">
                    <div class="card-body">
                        Dark
                        <div class="text-white-50 small">#5a5c69</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-lg-6 mb-4">

        <!-- Illustrations -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Categories</h6>
            </div>
            <div class="card-body">
                <table id="myTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Assets</th>
                            <th>Components</th>
                            <th>Accessories</th>
                            <th>Consumables</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td>ID</td>
                            <td>Name</td>
                            <td>Assets</td>
                            <td>Components</td>
                            <td>Accessories</td>
                            <th>Consumables</td>
                        </tr>
                    </tfoot>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Laptops</td>
                            <td>641</td>
                            <td>243</td>
                            <td>1021</td>
                            <td>5</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Tablets</td>
                            <td>341</td>
                            <td>112</td>
                            <td>46</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>IPads</td>
                            <td>223</td>
                            <td>121</td>
                            <td>54</td>
                            <td>0</td>
                        </tr>
                </table>
            </div>
        </div>

        <!-- Approach -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Development Approach</h6>
            </div>
            <div class="card-body">
                <p>SB Admin 2 makes extensive use of Bootstrap 4 utility classes in order to reduce
                    CSS bloat and poor page performance. Custom CSS classes are used to create
                    custom components and custom utility classes.</p>
                <p class="mb-0">Before working with this theme, you should become familiar with the
                    Bootstrap framework, especially the utility classes.</p>
            </div>
        </div>

    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('js/chart.js') }}"></script>
<script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
<script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>
<script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready( function () {
    $('#myTable').DataTable();
    } );
</script>
@endsection