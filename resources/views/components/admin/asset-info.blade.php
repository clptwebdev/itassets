<!-- Content Row -->
<div class="row">

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-2 col-md-3 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Assets Value(Total)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            @php
                            $assets = \App\Models\Asset::all();
                            $total = 0; $depreciation = 0;
                            foreach($assets as $asset){
                                $total = $total + $asset->purchased_cost;
                                $age = Carbon\Carbon::now()->floatDiffInYears($asset->purchased_date); 
                                $percentage = floor($age)*33.333; 
                                $dep = $asset->purchased_cost * ((100 - $percentage) / 100);
                                $depreciation += $dep; 
                            }
                            
                            @endphp
                            {{ '£'.round($total)}}
                            <small class="text-danger">(£{{ round($depreciation)}})</small>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-pound-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-3 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Assets(Total)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{($assetAmount) ?? null}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-laptop fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-2 col-md-3 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Requests</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">7</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-question fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-2 col-md-3 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Undeployable
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">51</div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 50%"
                                        aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-skull-crossbones fa-2x text-gray-300"></i>
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

    <div class="col-xl-2 col-md-3 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Audits Due</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $audits_due }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tools fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-3 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Overdue Audits</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $audits_over }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tools text-gray-300 fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
