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
        <!-- Custom styles for this template-->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <header id="header">
        <table width="100%"></i>
            <tr>
                <td width="15%" align="right" style="padding-left:10px;"><img id="logo" src="{{ asset('images/apollo-logo.jpg') }}" alt="Apollo Assets Manager"></td>
                <td width="45%" align="left">Apollo Asset Manangement<br><small>A Central Learning Partnership Trust (CLPT) System &copy; 2021</small></td>
                <td width="40%" align="right" style="padding-right: 10px;">
                    Report On: {{ \Carbon\Carbon::now()->format('d-m-Y - H:ia')}}<br>Report by: {{auth()->user()->name;}}
                </td>
            </tr>
        </table>
    </header>

        <div style="width: 65%; float: left;">
            <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr>
                        <th colspan="2">Device Information</th>
                    </tr>
                </thead>
                <tr>
                    <td>Device Name:</td>
                    <td>{{ $asset->model->name }}</td>
                </tr>
                <tr>
                    <td>Device Model N<span class="">o</span></td>
                    <td>{{ $asset->model->model_no }}</td>
                </tr>
                <tr>
                    <td>Device Serial N<span class="">o</span></td>
                    <td>{{ $asset->serial_no }}</td>
                </tr>
                @foreach($asset->fields as $field)
                    <tr>
                        <td>{{ $field->name ?? 'Unknown' }}</td>
                        <td>
                            @if($field->type == 'Checkbox')
                                @php($field_values = explode(',', $field->pivot->value))
                                <ul>
                                @foreach($field_values as $id=>$key)
                                    <li>{{ $key }}</li>
                                @endforeach
                                </ul>
                            @else
                            {{ $field->pivot->value }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
            </table>
        </div>
        <div style="width: 30%; float: right;">
            @if(isset($asset->model->photo->path))
                    <img src="{{ asset($asset->model->photo->path) ?? asset('images/svg/device-image.svg')}}" width="100%" alt="{{$asset->model->name}}">
                    @else
                    <img src="{{asset('images/svg/device-image.svg')}}" width="100%" alt="{{$asset->model->name}}">
                    @endif
                    <hr>
                    {!! '<img width="100%" height="100px" src="data:image/png;base64,' . DNS1D::getBarcodePNG($asset->asset_tag, 'C39+',3,33) . '" alt="barcode"   />' !!}
                    <p class="text-center font-weight-bold mx-4">Asset Tag: #{{ $asset->asset_tag }}</p>
        </div>
    </div>

</body>
</html>