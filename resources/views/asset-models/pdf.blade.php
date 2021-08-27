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
                <td align="left">Apollo Asset Manangement<br><small>A Central Learning Partnership Trust (CLPT) System &copy; 2021</small><br><strong>Asset Models</strong></td>
                <td align="right" style="padding-right: 10px;">
                    Report On: {{ \Carbon\Carbon::now()->format('d-m-Y at H:i')}}<br>Report by: {{auth()->user()->name;}}
                </td>
            </tr>
        </table>
    </header>
    <table id="assetsTable" width="100%" class="table table-striped">
        <thead>
        <tr>
            <th width="15%;">Name</th>
            <th width="15%;">Manufacturer</th>
            <th width="10%;">Mode No</th>
            <th width="10%;">Assets</th>
            <th width="50%;">Notes</th>
        </tr>
        </thead>
        
        <tbody>
        @foreach($models as $model)
            <tr>
                <td>{{ $model->name}}</td>
                <td class="text-left">{{ $model->manufacturer->name ?? 'N/A' }}</td>
                <td class="text-center">{{$model->model_no}}</td>
                <td class="text-center">{{$model->assets->count()}}</td>
                <td class="text-left">{{$model->notes}}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Name</th>
                <th>Manufacturer</th>
                <th>Mode No</th>
                <th>Assets</th>
                <th>Notes</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>