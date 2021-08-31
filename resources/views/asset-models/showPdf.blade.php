<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Asset Models Download</title>
    <!-- Custom styles for this template-->
    
        <!-- Custom styles for this template-->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap');

        body{
            font-size: 11px;
            font-family:Verdana, Geneva, Tahoma, sans-serif;
            color: #000;
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
                <td width="15%" align="right" style="padding-left:10px;"><img id="logo" src="{{ asset('images/apollo-logo.jpg') }}" alt="Apollo Assets Manager"></td>
                <td width="45%" align="left">Apollo Asset Manangement<br><small>A Central Learning Partnership Trust (CLPT) System &copy; 2021</small>
                    <br><strong>{{ $assetModel->name}}</strong>
                </td>
                <td width="40%" align="right" style="padding-right: 10px;">
                    Report On: {{ \Carbon\Carbon::now()->format('d-m-Y - H:ia')}}<br>Report by: {{auth()->user()->name;}}
                </td>
            </tr>
        </table>
    </header>

        <div style="width: 62%; pading-right: 3%; float: left;">
            @if($assetModel->photo()->exists())
                <img src="{{ asset($assetModel->photo->path) ?? asset('images/svg/device-image.svg')}}" width="200px" class="p-3" alt="{{$assetModel->name}}">
            @else
                <img src="{{asset('images/svg/device-image.svg')}}" width="100%" alt="{{$assetModel->name}}">
            @endif
            <hr>
            <table id="assetstable" class="table table-sm table-bordered table-striped">
                <thead>
                    <tr style="background-color: #454777; padding: 10px; color: #fff;">
                        <th colspan="2">Device Information</th>
                    </tr>
                </thead>
                <tr>
                    <td>Name:</td>
                    <td>{{ $assetModel->name }}</td>
                </tr>
                <tr>
                <tr>
                    <td>Device Model N<span class="">o</span></td>
                    <td>{{ $assetModel->model_no }}</td>
                </tr>
            </table>

            <table class="table table-sm table-bordered table-striped">
                <thead>
                <tr style="background-color: #454777; padding: 10px; color: #fff;">
                    <th >EOL (End of Life) </th>
                </tr>
                </thead>
                <tr>
                    <td><strong>{{ $assetModel->eol }} Months</strong></td>
                </tr>
            </table>

            <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr style="background-color: #454777; padding: 10px; color: #fff;">
                        <th>Notes:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $assetModel->notes}}</td>
                    </tr>
                </tbody>
            </table>

        </div>
        <div style="width: 32%; padding-left: 3%;float: right; border-left: solid 3px #CCC;">
            
        </div>
    </div>
    

@if($assetModel->assets()->exists())
<div class="page-break"></div>
<table class="table table-bordered table-striped ">
    <thead>
        <tr style="background-color: #454777; padding: 10px; color: #fff;"><th>Assigned Assets</th></tr>    
    </thead>                      
    <tbody>
            <tr>
                <th class="col-9 col-md-2"><small>Item</small></th>
                <th class="col-1 col-md-auto text-center"><small>Location</small></th>
                <th class="col-1 col-md-auto"><small>Tag</small></th>
                <th class="d-none d-xl-table-cell"><small>Date</small></th>
                <th class="d-none d-xl-table-cell text-center"><small>Cost</small>
            </tr>
           
    </tbody>
</table>
@endif
</body>
</html>