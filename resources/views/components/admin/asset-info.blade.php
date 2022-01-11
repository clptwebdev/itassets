
<!-- Content Row -->
<div class="d-flex p-2 mb-1 justify-content-around">
    <!-- Total-->
    <div class="w-50 row rounded p-2 pb-4" style="background-color: #EEE">
        <div class="col-12 text-dark text-xs font-weight-bold text-uppercase">Assets</div>
        <div class="col-lg-4">
            <div class="card border-left-lilac shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-lilac text-uppercase mb-1">
                                Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <small>Count: <span id="total_count" class="countup"></span></small><br>
                                £<span id="total_cost" class="countup"></span><br>
                                <small class="text-coral">£<span id="total_dep" class="countup"></small><br>
                                <span class="text-xs">*calculated depreciation</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-pound-sign fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                        </div>
                    </div>
                </div>
                <div class="stats_loading d-flex justify-content-center align-items-center" style="position: absolute; z-index: 2; width: 100%; height: 100%; top: 0; left: 0; background-color: rgba(255,255,255,0.8);">
                    <div class="spinner-border text-secondary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-lg-4">
            <div class="card border-left-coral shadow h-100 py-2 postion-relative">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-1">
                            <div class="text-xs font-weight-bold text-coral text-uppercase mb-1">
                                Assets</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <small>Total: <span id="assets_count" class="countup"></span></small><br>
                                £<span id="assets_cost" class="countup"></span><br>
                                <small class="text-coral">(£<span id="assets_dep" class="countup"></span>)*</small><br>
                                <span class="text-xs">*calculated depreciation</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tablet-alt fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                        </div>
                    </div>
                </div>
                <div class="stats_loading d-flex justify-content-center align-items-center" style="position: absolute; z-index: 2; width: 100%; height: 100%; top: 0; left: 0; background-color: rgba(255,255,255,0.8);">
                    <div class="spinner-border text-secondary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-left-blue shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-1">
                            <div class="text-xs font-weight-bold text-blue text-uppercase mb-1">
                                Accessories</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <small>Total: <span id="accessory_count" class="countup"></span></small><br>
                                £<span id="accessory_cost" class="countup"></span><br>
                                <small class="text-coral">(£<span id="accessory_dep" class="countup"></span>)</small><br>
                                <span class="text-xs">*calculated depreciation</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-keyboard fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                        </div>
                    </div>
                </div>
                <div class="stats_loading d-flex justify-content-center align-items-center" style="position: absolute; z-index: 2; width: 100%; height: 100%; top: 0; left: 0; background-color: rgba(255,255,255,0.8);">
                    <div class="spinner-border text-secondary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="w-50 row rounded p-2 pb-4">
        <div class="col-12 text-dark text-xs font-weight-bold text-uppercase">Other Items</div>
        <div class="col-lg-4">
            <div class="card border-left-green shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-1">
                            <div class="text-xs font-weight-bold text-green text-uppercase mb-1">
                                Components</div>
                                <small>Total Items: <span id="components_count" class="countup"></span></small><br>
                                £<span id="components_cost" class="countup"></span>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hdd fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                        </div>
                    </div>
                </div>
                <div class="stats_loading d-flex justify-content-center align-items-center" style="position: absolute; z-index: 2; width: 100%; height: 100%; top: 0; left: 0; background-color: rgba(255,255,255,0.8);">
                    <div class="spinner-border text-secondary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Requests -->
        <div class="col-lg-4">
            <div class="card border-left-yellow shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-yellow text-uppercase mb-1">
                                Consumables</div>
                                <small>Total Items: <span id="consumables_count" class="countup"></span></small><br>
                                £<span id="consumables_cost" class="countup"></span>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tint fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                        </div>
                    </div>
                </div>
                <div class="stats_loading d-flex justify-content-center align-items-center" style="position: absolute; z-index: 2; width: 100%; height: 100%; top: 0; left: 0; background-color: rgba(255,255,255,0.8);">
                    <div class="spinner-border text-secondary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Requests -->
        <div class="col-lg-4">
            <div class="card border-left-blue shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-blue text-uppercase mb-1">
                                Miscellaneous</div>
                                <small>Total Items: <span id="miscellanea_count" class="countup"></span></small><br>
                                £<span id="miscellanea_cost" class="countup"></span>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-question fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                        </div>
                    </div>
                </div>
                <div class="stats_loading d-flex justify-content-center align-items-center" style="position: absolute; z-index: 2; width: 100%; height: 100%; top: 0; left: 0; background-color: rgba(255,255,255,0.8);">
                    <div class="spinner-border text-secondary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Content Row -->
<div class="row p-4">
    <div class="col-12 text-dark text-xs font-weight-bold text-uppercase">Statistics</div>

    <!-- Requests -->
    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card bg-yellow shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold  text-uppercase mb-1">
                            New Requests</div>
                        <div class="h5 mb-0 font-weight-bold "><span id="requests_count" class="countup"></span></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tasks fa-2x d-md-none d-lg-inline-block"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card bg-green shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Transfers</div>
                        <div class="h5 mb-0 font-weight-bold"><span id="transfers_count" class="countup"></span></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exchange-alt fa-2x d-md-none d-lg-inline-block"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

      <!-- Earnings (Monthly) Card Example -->
      <div class="col-xl-2 col-md-4 mb-4">
        <div class="card bg-blue shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Archived</div>
                        <div class="h5 mb-0 font-weight-bold ">
                            <span id="archived_count" class="countup"></span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-archive fa-2x d-md-none d-lg-inline-block"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Undeployable -->
    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card bg-grey shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Deployable
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold"><span id="undeployable_count" class="countup"></span>%</div>
                            </div>

                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div id="undeployable_progress" class="progress-bar bg-green" role="progressbar" style="width: 0%"
                                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-skull-crossbones fa-2x d-md-none d-lg-inline-block"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card bg-yellow shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Audits Due</div>
                        <div class="h5 mb-0 font-weight-bold"><span id="audits_due_count" class="countup"></span></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tools fa-2x d-md-none d-lg-inline-block"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card bg-coral shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold  text-uppercase mb-1">
                            Overdue Audits</div>
                        <div class="h5 mb-0 font-weight-bold"><span id="audits_over_count" class="countup"></span></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tools  fa-2x d-md-none d-lg-inline-block"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
