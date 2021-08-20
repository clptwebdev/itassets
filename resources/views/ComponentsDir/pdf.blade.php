<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Components Download</title>
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
                <td align="left">Apollo Asset Manangement<br><small>A Central Learning Partnership Trust (CLPT) System &copy; 2021</small><br>Components</td>
                <td align="right" style="padding-right: 10px;">
                    Report On: {{ \Carbon\Carbon::now()->format('d-m-Y at H:i')}}<br>Report by: {{auth()->user()->name;}}
                </td>
            </tr>
        </table>
    </header>
    <table id="assetsTable" class="table table-striped" width="100%">
        <thead>
        <tr>
            <th><small>Name</small></th>
            <th class="text-center"><small>Location</small></th>
            <th class="text-center"><small>Manufacturers</small></th>
            <th><small>Purchased Date</small></th>
            <th><small>Purchased Cost</small></th>
            <th><small>Supplier</small></th>
            <th class="text-center"><small>Status</small></th>
            <th class="text-center"><small>Warranty</small></th>
        </tr>
        </thead>
        
        <tbody>
        @foreach($components as $component)

            <tr>
                <td>{{$component->name}}
                    <br>
                    <small>{{$component->serial_no}}</small>
                </td>
                <td class="text-center"><span style="color: {{ $component->location->icon ?? '#666'}}">{{$component->location->name ?? 'Unassigned'}}</span></td>
                <td class="text-center">{{$component->manufacturer->name ?? "N/A"}}</td>
                <td>{{\Carbon\Carbon::parse($component->purchased_date)->format("d/m/Y")}}</td>
                <td>{{$component->purchased_cost}}</td>
                <td>{{$component->supplier->name ?? 'N/A'}}</td>
                <td class="text-center">{{$component->status->name ??'N/A'}}</td>
                @php $warranty_end = \Carbon\Carbon::parse($component->purchased_date)->addMonths($component->warranty);@endphp
                <td class="text-center  d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
                    {{ $component->warranty }} Months

                    <br><small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</small>
                </td>
                
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th><small>Name</small></th>
                <th class="text-center"><small>Location</small></th>
                <th class="text-center"><small>Manufacturers</small></th>
                <th><small>Purchased Date</small></th>
                <th><small>Purchased Cost</small></th>
                <th><small>Supplier</small></th>
                <th class="text-center"><small>Status</small></th>
                <th class="text-center"><small>Warranty</small></th>
            </tr>
            </tfoot>
    </table>
    </table>
</body>
</html>