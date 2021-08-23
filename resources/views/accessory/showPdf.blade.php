<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Accessory Download</title>
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
                <td width="45%" align="left">
                    <small>Apollo Asset Manangement</small><br>
                    <small>A Central Learning Partnership Trust (CLPT) System &copy; 2021</small><br>
                    <strong>Accessory: {{ $accessory->name }}</strong>
                </td>
                <td width="40%" align="right" style="padding-right: 10px;">
                    Report On: {{ \Carbon\Carbon::now()->format('d-m-Y - H:ia')}}<br>Report by: {{auth()->user()->name;}}
                </td>
            </tr>
        </table>
    </header>

        <div style="width: 62%; pading-right: 3%; float: left;">
            <table id="assetstable" class="table table-sm table-bordered table-striped">
                <thead>
                    <tr style="background-color: #454777; padding: 10px; color: #fff;">
                        <th colspan="2">Information</th>
                    </tr>
                </thead>
                <tr>
                    <td>Name:</td>
                    <td>{{ $accessory->name }}</td>
                </tr>
                <tr>
                    <td>Serial N<span class="">o</span></td>
                    <td>{{ $accessory->serial_no }}</td>
                </tr>
            </table>

            <table class="table table-sm table-bordered table-striped">
                <thead>
                <tr style="background-color: #454777; padding: 10px; color: #fff;">
                    <th colspan="3">Status </th>
                </tr>
                </thead>
                <tr>
                    <td>Status: </td>
                    <td><strong>{{ $accessory->status->name}}</strong></td>
                    <td class="text-right"></td>
                </tr>
            </table>

            <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr style="background-color: #454777; padding: 10px; color: #fff;">
                        <th colspan="2">Added by:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $accessory->user->name ?? 'Unkown'}}</td>
                        <td>{{ $accessory->created_at}}</td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr style="background-color: #454777; padding: 10px; color: #fff;">
                        <th>Notes:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $accessory->notes ?? 'Unkown'}}</td>
                    </tr>
                </tbody>
            </table>
            @if($accessory->category()->exists())
            <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr style="background-color: #454777; padding: 10px; color: #fff;">
                        <th>Categories:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            @foreach($accessory->category as $category)
                            <strong class="font-weight-bold d-inline-block btn-sm btn-light shadow-sm p-1 m-2"><small>{{ $category->name}}</small></strong>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            @endif

        </div>
        <div style="width: 32%; padding-left: 3%;float: right; border-left: solid 3px #CCC;">
            <table class="" width="100%" style="border:none;">
                <thead>
                    <tr style="background-color: #454777; padding: 10px; color: #fff;">
                        <th  style="padding: 5px;">Manufacturer Information</th>
                    </tr>  
                </thead>
                <tbody>
                    @if($accessory->photo()->exists())
                    <tr>
                        <td style="padding: 5px;">
                            <img src="{{ asset($accessory->photo->path) }}" width="100%" alt="{{$accessory->name}}">
                        </td>
                    </tr>
                    @endif
                    <?php $manufacturer = $accessory->manufacturer; ?>
                    @if($manufacturer->photo()->exists())
                    <tr>
                        <td style="padding: 5px;">
                            <img src="{{ asset($manufacturer->photo->path)}}" width="70%" alt="{{$manufacturer->name}}">
                        </td>
                    </tr>
                    @endif
                    <tr><td style="padding: 5px;"><strong>{{ $manufacturer->name }}</strong></td></tr>
                    <tr><td style="padding: 5px;">Tel: {{ $manufacturer->supportPhone }}</td></tr>
                    <tr><td style="padding: 5px;">Email: {{ $manufacturer->supportEmail }}</td></tr>
                    <tr><td style="padding: 5px;">URL: {{ $manufacturer->supportUrl }}</td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <section class="d-block" style="margin-top: 50px; clear: both;">
    <div class="w-100 d-block">
        <table class="table table-bordered table-striped" width="100%">
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th colspan="2">Purchase Information</th>
            </tr>
            <tr>
                <td class="col-4">Order N<sup>o</sup>:</td>
                <td class="col-8"> {{$accessory->order_no }}</td>
            </tr>
            <tr>
                <td>Supplier:</td>
                <td>
                    {{ $accessory->supplier->name}}<br>
                    {{ $accessory->supplier->address_1 }}<br>
                    {{ $accessory->supplier->address_2 }}<br>
                    {{ $accessory->supplier->city }}<br>
                    {{ $accessory->supplier->county }}<br>
                    {{ $accessory->supplier->postcode }}<br>
                </td>
            </tr>
            <tr>
                <td>Date of Purchase:</td>
                <td>
                    <?php $purchase_date = \Carbon\Carbon::parse($accessory->purchased_date);?>
                    {{ $purchase_date->format('d/m/Y')}}
                </td>
            </tr>
            <tr>
                <td>Warranty</td>
                <td>
                    <?php $warranty_end = \Carbon\Carbon::parse($accessory->purchased_date)->addMonths($accessory->warranty);?>
                    {{ $accessory->warranty }} Month(s) - <strong>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</strong>
                </td>
            </tr>
            <tr>
                <td>Purchase Cost:</td>
                <td>£{{ $accessory->purchased_cost }}</td>
            </tr>
        </table>
    </div>
    @if($accessory->location()->exists())
    <div class="page-break"></div>
    <div class="w-100 d-block">
        
        <table class="table table-bordered table-striped" width="100%">
            <tr style="background-color: #454777; padding: 10px; color: #fff;"><td>Location Information</td></tr>
            @if($accessory->location->photo()->exists())
            <tr>
                <td class="text-center p-1">
                    <div style="width: 150px; overflow:hidden; margin-left: auto; margin-right: auto;">
                        <img src="{{ asset($accessory->location->photo->path)}}" width="100%" title="{{ $accessory->location->name}}">
                    </div>
                </td>
            </tr>
            @endif
            <tr><td style="color: {{$accessory->location->icon}};"><strong>{{ $accessory->location->name }}</strong></td></tr>
            <tr><td>{{ $accessory->location->address_1 }}</strong></td></tr>
            @if($accessory->location->address_2 != "")
            <tr><td>{{ $accessory->location->address_2 }}</strong></td></tr>
            @endif
            <tr><td>{{ $accessory->location->city }}</td></tr>
            <tr><td>{{ $accessory->location->county }}</td></tr>
            <tr><td>{{ $accessory->location->postcode }}</td></tr>
        </table>

    </div>
    @endif
</section>

@if($accessory->comment()->exists())
<div class="page-break"></div>
<p>Comments</p>
<table class="table table-bordered table-striped ">
    <thead>
        <tr style="background-color: #454777; padding: 10px; color: #fff;"><th>Recent Actvity</th></tr>    
    </thead>                      
    <tbody>
        
        @foreach($accessory->comment as $comment)
        <tr>
            <td class="text-left"><strong>{{$comment->title}}</strong><br>{{ $comment->comment }}<br><span class="text-info">{{ $comment->user->name }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $comment->created_at, 'Europe/London');}}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@if($accessory->logs()->exists())
<div class="page-break"></div>
<table class="table table-bordered table-striped ">
    <thead>
        <tr style="background-color: #454777; padding: 10px; color: #fff;"><th>Recent Actvity</th></tr>    
    </thead>                      
    <tbody>
        @foreach($accessory->logs()->orderBy('created_at', 'desc')->take(30)->get() as $log)
        <tr>
            <td class="text-left">{{$log->data}}<br><span class="text-info">{{ $log->user->name }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $log->created_at, 'Europe/London');}}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
</body>
</html>