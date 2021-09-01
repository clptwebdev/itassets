<!-- Content Row -->
<div class="row">
    @php
    if(auth()->user()->role_id == 1){
        $assets = \App\Models\Asset::all();
    }else{
        $assets = auth()->user()->location_assets;
    }
    @endphp
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Assets Value(Total)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            @php
                            $total = 0; $depreciation = 0;
                            foreach($assets as $asset){
                                $total = $total + $asset->purchased_cost;
                                if($asset->asset_model != 0){
                                    $eol = Carbon\Carbon::parse($asset->purchased_date)->addYears($asset->model->depreciation->years);
                                    if($eol->isPast()){}else{
                                        $age = Carbon\Carbon::now()->floatDiffInYears($asset->purchased_date);
                                        $percent = 100 / $asset->model->depreciation->years;
                                        $percentage = floor($age)*$percent;
                                        $dep = $asset->purchased_cost * ((100 - $percentage) / 100);
                                        $depreciation += $dep;
                                    }
                                }else{
                                    $depreciation += $asset->purchased_cost;
                                }
                            }

                            @endphp
                            {{ '£'.round($total)}}
                            <small class="text-danger">(£{{ round($depreciation)}})</small>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-pound-sign fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Assets(Total)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$assets->count() ?? null}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-laptop fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Requests</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">7</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-question fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($assets->count() != 0)
    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Undeployable
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
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $ud}}</div>
                            </div>

                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    @if(!$ud === 0 )

                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ ($ud/$total) * 100 }}%"
                                        aria-valuenow="{{ ($ud/$total) * 100 }}" aria-valuemin="0" aria-valuemax="100"></div>@endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-skull-crossbones fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
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
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Audits Due</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $audits_due }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tools fa-2x text-gray-300 d-md-none d-lg-inline-block"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Overdue Audits</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $audits_over }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tools text-gray-300 fa-2x d-md-none d-lg-inline-block"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
