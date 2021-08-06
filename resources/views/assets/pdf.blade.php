<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Assets Download</title>
    <!-- Custom styles for this template-->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap');

        body{
            font-size: 11px;
            font-family: 'Roboto', sans-serif;
        }

        #header{
            background-color: #454777;
            width: 100%;
            margin-bottom: 30px;
            color: #fff;
            font-size: 14px;
        }

        #logo{
            max-height: 100px;
        }

        #assetsTable{
            border: solid 1px #666;
            border-collapse: collapse;
        }

        #assetsTable th{
            padding: 5px;
            background-color: #454777;
            color: #FFF;
            border: solid 1px #666;
        }

        #assetsTable td{
            border: solid 1px #AAA;
            padding: 5px;
        }

        .page-break {
            page-break-after: always;
        }
        </style>
</head>
<body>
    <header id="header">
        <table width="100%"></i>
            <tr>
                <td align="left" style="padding-left:10px;"><img id="logo" src="{{ asset('images/apollo-logo.jpg') }}" alt="Apollo Assets Manager"></td>
                <td align="left">Apollo Asset Manangement<br><small>A Central Learning Partnership Trust (CLPT) System &copy; 2021</small></td>
                <td align="right" style="padding-right: 10px;">
                    Report On: {{ \Carbon\Carbon::now()->format('d-m-Y at H:i')}}<br>Report by: {{auth()->user()->name;}}
                </td>
            </tr>
        </table>
    </header>
    <table id="assetsTable" width="100%" class="table table-striped">
        <thead>
        <tr>
            <th width="15%;">Item</th>
            <th width="15%;">Location</th>
            <th width="15%;">Tag</th>
            <th width="10%;">Manufacturer</th>
            <th width="5%;">Date</th>
            <th width="5%;">Cost</th>
            <th width="10%;">Supplier</th>
            <th width="10%;">Warranty (M)</th>
            <th width="15%;">Audit Due</th>
        </tr>
        </thead>
        
        <tbody>
        @foreach($assets as $asset)
            <tr>
                <td>{{ $asset->model->name ?? 'No Model'}}<br><small class="d-none d-md-inline-block">{{ $asset->serial_no }}</small></td>
                <td class="text-center"><span style="color: {{ $asset->location->icon ?? '#666'}}">{{$asset->location->name ?? 'Unassigned'}}</span>
                </td>
                <td align="center">
                    {!! '<div id="barcode"><img width="120px" height="30px" src="data:image/png;base64,' . DNS1D::getBarcodePNG($asset->asset_tag, 'C39+',3,33) . '" alt="barcode"   /></div>' !!}
                    <span style="font-weight: 800">{{ $asset->asset_tag }}</span></td>
                <td class="text-center d-none d-xl-table-cell">{{ $asset->model->manufacturer->name ?? 'N/A' }}</td>
                <td class="d-none d-md-table-cell" data-sort="{{ strtotime($asset->purchased_date)}}">{{ \Carbon\Carbon::parse($asset->purchased_date)->format('d/m/Y')}}</td>
                <td class="text-center  d-none d-xl-table-cell">
                    
                    @if($asset->model)
                    <br>
                    @php
                    $eol = Carbon\Carbon::parse($asset->purchased_date)->addYears($asset->model->depreciation->years);
                    if($eol->isPast()){
                        $dep = 0;
                    }else{
                        $age = Carbon\Carbon::now()->floatDiffInYears($asset->purchased_date);
                        $percent = 100 / $asset->model->depreciation->years;
                        $percentage = floor($age)*$percent;
                        $dep = $asset->purchased_cost * ((100 - $percentage) / 100);
                    }
                    @endphp
                    £{{ number_format($dep, 2)}}
                    <small>*£{{ $asset->purchased_cost }} (Original)</small>
                    @else
                    £{{ $asset->purchased_cost }}  
                    @endif                    
                </td>
                <td class="text-center d-none d-xl-table-cell">{{$asset->supplier->name ?? "N/A"}}</td>
                @php $warranty_end = \Carbon\Carbon::parse($asset->purchased_date)->addMonths($asset->warranty);@endphp
                <td class="text-center  d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
                    {{ $asset->warranty }} Months
                    @php
                        $remaining = round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end));
                    @endphp
                    <br><small style="color:@if($remaining <= 0 ){{'#dc3545'}}@elseif($remaining < 6){{ '#ffc107'}}@else{{'#28a745'}}@endif;">{{ $remaining }} Remaining</small>
                </td>
                <td class="text-center d-none d-xl-table-cell" data-sort="{{ strtotime($asset->audit_date)}}">
                    @if(\Carbon\Carbon::parse($asset->audit_date)->isPast())
                        <span style="color: #dc3545">{{\Carbon\Carbon::parse($asset->audit_date)->format('d/m/Y') }}<br><small>Audit Overdue</small></span>
                    @else
                        <?php $age = Carbon\Carbon::now()->floatDiffInDays($asset->audit_date);?>
                        @switch(true)
                            @case($age < 31) <span style="color: #ffc107">{{ \Carbon\Carbon::parse($asset->audit_date)->format('d/m/Y') }}<br><small>Audit Due Soon</small></span>
                                @break
                            @default
                                <span style="color: #666">{{ \Carbon\Carbon::parse($asset->audit_date)->format('d/m/Y') }}<br><small>Audit due in {{floor($age)}} days</small></span>
                        @endswitch
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th><small>Item</small></th>
                <th><small>Location</small></th>
                <th><small>Tag</small></th>
                <th class="d-none d-xl-table-cell"><small>Manufacturer</small></th>
                <th class=" d-none d-xl-table-cell"><small>Date</small></th>
                <th class=" d-none d-xl-table-cell"><small>Cost</small></th>
                <th class=" d-none d-xl-table-cell"><small>Supplier</small></th>
                <th class=" d-none d-xl-table-cell"><small>Warranty (M)</small></th>
                <th class="text-center  d-none d-md-table-cell"><small>Audit Due</small></th>
            </tr>
        </tfoot>
    </table>
</body>
</html>