@extends('layouts.pdf-reports')

@section('title', 'Location Report')

@section('page', $accessory->name)

@section('user', $user->name)

@section('content')

<section>
    <table class="table">
        <thead>
            <tr style="background-color: #454777; color: #fff;">
                <th colspan="3" style="padding: 5px;">Information</th>
            </tr>
        </thead>
        @if($accessory->photo()->exists())
        <tr>
            <td rowspan="6" width="110px" align="center">
                <img src="{{ asset($accessory->photo->path) }}" width="100px" alt="{{$accessory->name}}">
            </td>
            <td>Name:</td>
            <td>{{ $accessory->name }}</td>
        </tr>
        @endif
        <tr>
            <td>Serial N<span class="">o</span></td>
            <td>{{ $accessory->serial_no }}</td>
        </tr>
        <tr style="background-color: #454777; color: #fff;">
            <td colspan="2" style="padding: 5px;">Status</td>
        </tr>
        <tr>
            <td>Status: </td>
            <td><strong>{{ $accessory->status->name}}</strong></td>
        </tr>
        <tr style="background-color: #454777; color: #fff;">
            <td colspan="2" style="padding: 5px;">Added by:</td>
        </tr>
        <tr>
            <td>{{ $accessory->user->name ?? 'Unkown'}}</td>
            <td>{{ $accessory->created_at}}</td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th>Notes:</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $accessory->notes ?? 'Unknown'}}</td>
            </tr>
        </tbody>
    </table>

    @if($accessory->category()->exists())
    <table class="table">
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
    
</section>
<div class="page-break"></div>
    @if($accessory->manufacturer()->exists())
    <table class="table" width="100%">
        <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th  style="padding: 5px;">Manufacturer Information</th>
            </tr>  
        </thead>
        <tbody>
            <tr><td style="padding: 5px;"><strong>{{ $accessory->manufacturer->name }}</strong></td></tr>
            <tr><td style="padding: 5px;">Tel: {{ $accessory->manufacturer->supportPhone }}</td></tr>
            <tr><td style="padding: 5px;">Email: {{ $accessory->manufacturer->supportEmail }}</td></tr>
            <tr><td style="padding: 5px;">URL: {{ $accessory->manufacturer->supportUrl }}</td></tr>
            
        </tbody>
    </table>
    @endif

    <table class="table" width="100%">
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
                @if($accessory->supplier()->exists())
                {{ $accessory->supplier->name}}<br>
                {{ $accessory->supplier->address_1 }}<br>
                {{ $accessory->supplier->address_2 }}<br>
                {{ $accessory->supplier->city }}<br>
                {{ $accessory->supplier->county }}<br>
                {{ $accessory->supplier->postcode }}<br>
                @else
                    {{ 'No Supplier Information'}}
                @endif
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
@if($accessory->location()->exists())
    <table class="table" width="100%">
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
@endif

@if($accessory->comment()->exists())
    <div class="page-break"></div>
    <table class="table">
        <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;"><th>Comments</th></tr>    
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
    <table class="table">
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
@endsection