@php

    //Assets
    $total = 0; $depreciation = 0;
    foreach($assets as $asset){
    	$total += $asset->purchased_cost;
    	$depreciation += $asset->depreciation_value;
    }
    $asset_total = $total; $asset_depreciation = $depreciation;
    //Accessories
    $total = 0; $depreciation = 0;
    foreach($accessories as $accessory){
        $total += $accessory->purchased_cost;
    	$depreciation += $accessory->depreciation_value();
    }
    $accessory_total = $total; $accessory_depreciation = $depreciation;

    $total = 0;
    foreach($components as $component){
        $total = $total + $component->purchased_cost;
    }
    $component_total = $total;

    $total = 0;
    foreach($consumables as $consumable){
        $total = $total + $consumable->purchased_cost;
    }
    $consumable_total = $total;

    $total = 0;
    foreach($miscellaneous as $miscellanea){
        $total = $total + $miscellanea->purchased_cost;
    }
    $miscellanea_total = $total;




@endphp

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
                                <small>Count: {{ $assets->count() + $accessories->count()}}</small><br>
                                {{ '£'.round($asset_total + $accessory_total)}}
                                <small class="text-coral">(£{{ round($asset_depreciation + $accessory_depreciation)}})*</small><br>
                                <span class="text-xs">*calculated depreciation</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-pound-sign fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-lg-4">
            <div class="card border-left-coral shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-1">
                            <div class="text-xs font-weight-bold text-coral text-uppercase mb-1">
                                Assets</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <small>Total: {{ $assets->count()}}</small><br>
                                <small>{{ '£'.round($asset_total)}}</small>
                                <small class="text-coral">(£{{ round($asset_depreciation)}})*</small><br>
                                <span class="text-xs">*calculated depreciation</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tablet-alt fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                        </div>
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
                                <small>Total: {{ $accessories->count()}}</small><br>
                                {{ '£'.round($accessory_total)}}
                                <small class="text-coral">(£{{ round($accessory_depreciation)}})*</small><br>
                                <span class="text-xs">*calculated depreciation</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-keyboard fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                        </div>
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
                                <small>Total Spent: {{ $components->count()}}</small><br>
                                {{ '£'.round($component_total)}}
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hdd fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                        </div>
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
                                <small>Total Spent: {{ $consumables->count()}}</small><br>
                                {{ '£'.round($consumable_total)}}
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tint fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                        </div>
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
                                <small>Total Spent: {{ $miscellaneous->count()}}</small><br>
                                {{ '£'.round($miscellanea_total)}}
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-question fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                        </div>
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
                        <div class="h5 mb-0 font-weight-bold ">{{ $requests ?? '0' }}</div>
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
                        <div class="h5 mb-0 font-weight-bold">{{$transfers ?? '0'}}</div>
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
                        {{ $archived ?? '0'}}
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
    @if($assets->count() != 0)
    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card bg-grey shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Undeployable
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                @php
                                    $ud = 0; $total = $assets->count();
                                    foreach($assets as $asset){
                                        if($asset->status_id != 0 && $asset->status->deployable == 0){
                                            $ud++;
                                        }
                                    }
                                @endphp
                                <div class="h5 mb-0 mr-3 font-weight-bold">{{ $ud}}</div>
                            </div>

                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-coral" role="progressbar" style="width: {{ ($ud/$total) * 100 }}%"
                                        aria-valuenow="{{ ($ud/$total) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>
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

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card bg-yellow shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Audits Due</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $audits_due }}</div>
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
                        <div class="h5 mb-0 font-weight-bold">{{ $audits_over }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tools  fa-2x d-md-none d-lg-inline-block"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
