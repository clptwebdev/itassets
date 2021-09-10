@extends('layouts.pdf-reports')

@section('title', 'Micellaneous Report')

@section('page', $miscellanea->name)

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
                    <td>{{ $miscellanea->name }}</td>
                </tr>
                <tr>
                    <td>Serial N<span class="">o</span></td>
                    <td>{{ $miscellanea->serial_no }}</td>
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
                    <td><strong>{{ $miscellanea->status->name ?? 'N/A'}}</strong></td>
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
                        <td>{{ $miscellanea->notes ?? 'Unkown'}}</td>
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
                        <td>{{ $miscellanea->user->name ?? 'Unkown'}}</td>
                        <td>{{ $miscellanea->created_at}}</td>
                    </tr>
                </tbody>
            </table>
            @if($miscellanea->category()->exists())
            <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr style="background-color: #454777; padding: 10px; color: #fff;">
                        <th>Categories:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            @foreach($miscellanea->category as $category)
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
                    @if($miscellanea->photo()->exists())
                    <tr>
                        <td style="padding: 5px;">
                            <img src="{{ asset($miscellanea->photo->path) }}" width="100%" alt="{{$miscellanea->name}}">
                        </td>
                    </tr>
                    @endif
                    <?php $manufacturer = $miscellanea->manufacturer; ?>
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
                <td class="col-8"> {{$miscellanea->order_no }}</td>
            </tr>
            <tr>
                <td>Supplier:</td>
                <td>
                    @if($miscellanea->supplier()->exists())
                    {{ $miscellanea->supplier->name}}<br>
                    {{ $miscellanea->supplier->address_1 }}<br>
                    {{ $miscellanea->supplier->address_2 }}<br>
                    {{ $miscellanea->supplier->city }}<br>
                    {{ $miscellanea->supplier->county }}<br>
                    {{ $miscellanea->supplier->postcode }}<br>
                    @else
                    <p>No Supplier Details</p>
                    @endif
                </td>
            </tr>
            <tr>
                <td>Date of Purchase:</td>
                <td>
                    <?php $purchase_date = \Carbon\Carbon::parse($miscellanea->purchased_date);?>
                    {{ $purchase_date->format('d/m/Y')}}
                </td>
            </tr>
            <tr>
                <td>Warranty</td>
                <td>
                    <?php $warranty_end = \Carbon\Carbon::parse($miscellanea->purchased_date)->addMonths($miscellanea->warranty);?>
                    {{ $miscellanea->warranty }} Month(s) - <strong>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</strong>
                </td>
            </tr>
            <tr>
                <td>Purchase Cost:</td>
                <td>£{{ $miscellanea->purchased_cost }}</td>
            </tr>
        </table>
    </div>
    @if($miscellanea->location()->exists())
    <div class="page-break"></div>
    <div class="w-100 d-block">
        
        <table class="table table-bordered table-striped" width="100%">
            <tr style="background-color: #454777; padding: 10px; color: #fff;"><td>Location Information</td></tr>
            @if($miscellanea->location->photo()->exists())
            <tr>
                <td class="text-center p-1">
                    <div style="width: 150px; overflow:hidden; margin-left: auto; margin-right: auto;">
                        <img src="{{ asset($miscellanea->location->photo->path)}}" width="100%" title="{{ $miscellanea->location->name}}">
                    </div>
                </td>
            </tr>
            @endif
            <tr><td style="color: {{$miscellanea->location->icon}};"><strong>{{ $miscellanea->location->name }}</strong></td></tr>
            <tr><td>{{ $miscellanea->location->address_1 }}</strong></td></tr>
            @if($miscellanea->location->address_2 != "")
            <tr><td>{{ $miscellanea->location->address_2 }}</strong></td></tr>
            @endif
            <tr><td>{{ $miscellanea->location->city }}</td></tr>
            <tr><td>{{ $miscellanea->location->county }}</td></tr>
            <tr><td>{{ $miscellanea->location->postcode }}</td></tr>
        </table>

    </div>
    @endif
</section>

@if($miscellanea->comment()->exists())
<div class="page-break"></div>
<p>Comments</p>
<table class="table table-bordered table-striped ">
    <thead>
        <tr style="background-color: #454777; padding: 10px; color: #fff;"><th>Recent Actvity</th></tr>    
    </thead>                      
    <tbody>
        
        @foreach($miscellanea->comment as $comment)
        <tr>
            <td class="text-left"><strong>{{$comment->title}}</strong><br>{{ $comment->comment }}<br><span class="text-info">{{ $comment->user->name }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $comment->created_at, 'Europe/London');}}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@if($miscellanea->logs()->exists())
<div class="page-break"></div>
<table class="table table-bordered table-striped ">
    <thead>
        <tr style="background-color: #454777; padding: 10px; color: #fff;"><th>Recent Actvity</th></tr>    
    </thead>                      
    <tbody>
        @foreach($miscellanea->logs()->orderBy('created_at', 'desc')->take(30)->get() as $log)
        <tr>
            <td class="text-left">{{$log->data}}<br><span class="text-info">{{ $log->user->name }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $log->created_at, 'Europe/London');}}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection