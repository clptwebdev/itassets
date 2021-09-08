@extends('layouts.pdf-reports')

@section('title', 'Consumable Report')

@section('page', $consumable->name)

@section('content')

        <div style="width: 62%; pading-right: 3%; float: left;">
            <table id="assetstable" class="table table-sm table-bordered table-striped">
                <thead>
                    <tr style="background-color: #454777; padding: 10px; color: #fff;">
                        <th colspan="2">Information</th>
                    </tr>
                </thead>
                <tr>
                    <td>Name:</td>
                    <td>{{ $consumable->name }}</td>
                </tr>
                <tr>
                    <td>Serial N<span class="">o</span></td>
                    <td>{{ $consumable->serial_no }}</td>
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
                    <td><strong>{{ $consumable->status->name ?? 'N/A'}}</strong></td>
                    <td class="text-right"></td>
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
                        <td>{{ $consumable->notes ?? 'Unkown'}}</td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr style="background-color: #454777; padding: 10px; color: #fff;">
                        <th colspan="2">Added by:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $consumable->user->name ?? 'Unkown'}}</td>
                        <td>{{ $consumable->created_at}}</td>
                    </tr>
                </tbody>
            </table>
            @if($consumable->category()->exists())
            <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr style="background-color: #454777; padding: 10px; color: #fff;">
                        <th>Categories:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            @foreach($consumable->category as $category)
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
                    @if($consumable->photo()->exists())
                    <tr>
                        <td style="padding: 5px;">
                            <img src="{{ asset($consumable->photo->path) }}" width="100%" alt="{{$consumable->name}}">
                        </td>
                    </tr>
                    @endif
                    <?php $manufacturer = $consumable->manufacturer; ?>
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
                <td class="col-8"> {{$consumable->order_no }}</td>
            </tr>
            <tr>
                <td>Supplier:</td>
                <td>
                    {{ $consumable->supplier->name}}<br>
                    {{ $consumable->supplier->address_1 }}<br>
                    {{ $consumable->supplier->address_2 }}<br>
                    {{ $consumable->supplier->city }}<br>
                    {{ $consumable->supplier->county }}<br>
                    {{ $consumable->supplier->postcode }}<br>
                </td>
            </tr>
            <tr>
                <td>Date of Purchase:</td>
                <td>
                    <?php $purchase_date = \Carbon\Carbon::parse($consumable->purchased_date);?>
                    {{ $purchase_date->format('d/m/Y')}}
                </td>
            </tr>
            <tr>
                <td>Warranty</td>
                <td>
                    <?php $warranty_end = \Carbon\Carbon::parse($consumable->purchased_date)->addMonths($consumable->warranty);?>
                    {{ $consumable->warranty }} Month(s) - <strong>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</strong>
                </td>
            </tr>
            <tr>
                <td>Purchase Cost:</td>
                <td>£{{ $consumable->purchased_cost }}</td>
            </tr>
        </table>
    </div>
    @if($consumable->location()->exists())
    <div class="page-break"></div>
    <div class="w-100 d-block">
        
        <table class="table table-bordered table-striped" width="100%">
            <tr style="background-color: #454777; padding: 10px; color: #fff;"><td>Location Information</td></tr>
            @if($consumable->location->photo()->exists())
            <tr>
                <td class="text-center p-1">
                    <div style="width: 150px; overflow:hidden; margin-left: auto; margin-right: auto;">
                        <img src="{{ asset($consumable->location->photo->path)}}" width="100%" title="{{ $consumable->location->name}}">
                    </div>
                </td>
            </tr>
            @endif
            <tr><td style="color: {{$consumable->location->icon}};"><strong>{{ $consumable->location->name }}</strong></td></tr>
            <tr><td>{{ $consumable->location->address_1 }}</strong></td></tr>
            @if($consumable->location->address_2 != "")
            <tr><td>{{ $consumable->location->address_2 }}</strong></td></tr>
            @endif
            <tr><td>{{ $consumable->location->city }}</td></tr>
            <tr><td>{{ $consumable->location->county }}</td></tr>
            <tr><td>{{ $consumable->location->postcode }}</td></tr>
        </table>

    </div>
    @endif
</section>

@if($consumable->comment()->exists())
<div class="page-break"></div>
<p>Comments</p>
<table class="table table-bordered table-striped ">
    <thead>
        <tr style="background-color: #454777; padding: 10px; color: #fff;"><th>Recent Actvity</th></tr>    
    </thead>                      
    <tbody>
        
        @foreach($consumable->comment as $comment)
        <tr>
            <td class="text-left"><strong>{{$comment->title}}</strong><br>{{ $comment->comment }}<br><span class="text-info">{{ $comment->user->name }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $comment->created_at, 'Europe/London');}}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@if($consumable->logs()->exists())
<div class="page-break"></div>
<table class="table table-bordered table-striped ">
    <thead>
        <tr style="background-color: #454777; padding: 10px; color: #fff;"><th>Recent Actvity</th></tr>    
    </thead>                      
    <tbody>
        @foreach($consumable->logs()->orderBy('created_at', 'desc')->take(30)->get() as $log)
        <tr>
            <td class="text-left">{{$log->data}}<br><span class="text-info">{{ $log->user->name }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $log->created_at, 'Europe/London');}}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection