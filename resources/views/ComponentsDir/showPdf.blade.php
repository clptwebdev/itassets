@extends('layouts.pdf-reports')

@section('title', 'Component Report')

@section('page', $component->name)

@section('user', $user->name)

@section('content')

<section>
    <table class="table">
        <thead>
            <tr style="background-color: #454777; color: #fff;">
                <th colspan="3" style="padding: 5px;">Information</th>
            </tr>
        </thead>
       
        <tr>
            <td rowspan="6" width="110px" align="center">
                @if($component->photo()->exists())
                <img src="{{ asset($component->photo->path) }}" width="100px" alt="{{$component->name}}">
                @else
                <span style="width: 100px; height: 100px; background-colour: #222;">No Image Available</span>
                @endif
            </td>
            <td>Name:</td>
            <td>{{ $component->name }}</td>
        </tr>
        <tr>
            <td>Serial N<span class="">o</span></td>
            <td>{{ $component->serial_no }}</td>
        </tr>
        <tr style="background-color: #454777; color: #fff;">
            <td colspan="2" style="padding: 5px;">Status</td>
        </tr>
        <tr>
            <td>Status: </td>
            <td><strong>{{ $component->status->name}}</strong></td>
        </tr>
        <tr style="background-color: #454777; color: #fff;">
            <td colspan="2" style="padding: 5px;">Added by:</td>
        </tr>
        <tr>
            <td>{{ $component->user->name ?? 'Unkown'}}</td>
            <td>{{ $component->created_at}}</td>
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
                <td>{{ $component->notes ?? 'Unknown'}}</td>
            </tr>
        </tbody>
    </table>

    @if($component->category()->exists())
    <table class="table">
        <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th>Categories:</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    @foreach($component->category as $category)
                    <strong class="font-weight-bold d-inline-block btn-sm btn-light shadow-sm p-1 m-2"><small>{{ $category->name}}</small></strong>
                    @endforeach
                </td>
            </tr>
        </tbody>
    </table>
    @endif
    
</section>
<div class="page-break"></div>
    @if($component->manufacturer()->exists())
    <table class="table" width="100%">
        <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;">
                <th  style="padding: 5px;">Manufacturer Information</th>
            </tr>  
        </thead>
        <tbody>
            <tr><td style="padding: 5px;"><strong>{{ $component->manufacturer->name }}</strong></td></tr>
            <tr><td style="padding: 5px;">Tel: {{ $component->manufacturer->supportPhone }}</td></tr>
            <tr><td style="padding: 5px;">Email: {{ $component->manufacturer->supportEmail }}</td></tr>
            <tr><td style="padding: 5px;">URL: {{ $component->manufacturer->supportUrl }}</td></tr>
            
        </tbody>
    </table>
    @endif

    <table class="table" width="100%">
        <tr style="background-color: #454777; padding: 10px; color: #fff;">
            <th colspan="2">Purchase Information</th>
        </tr>
        <tr>
            <td class="col-4">Order N<sup>o</sup>:</td>
            <td class="col-8"> {{$component->order_no }}</td>
        </tr>
        <tr>
            <td>Supplier:</td>
            <td>
                @if($component->supplier()->exists())
                {{ $component->supplier->name}}<br>
                {{ $component->supplier->address_1 }}<br>
                {{ $component->supplier->address_2 }}<br>
                {{ $component->supplier->city }}<br>
                {{ $component->supplier->county }}<br>
                {{ $component->supplier->postcode }}<br>
                @else
                    {{ 'No Supplier Information'}}
                @endif
            </td>
        </tr>
        <tr>
            <td>Date of Purchase:</td>
            <td>
                <?php $purchase_date = \Carbon\Carbon::parse($component->purchased_date);?>
                {{ $purchase_date->format('d/m/Y')}}
            </td>
        </tr>
        <tr>
            <td>Warranty</td>
            <td>
                <?php $warranty_end = \Carbon\Carbon::parse($component->purchased_date)->addMonths($component->warranty);?>
                {{ $component->warranty }} Month(s) - <strong>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</strong>
            </td>
        </tr>
        <tr>
            <td>Purchase Cost:</td>
            <td>£{{ $component->purchased_cost }}</td>
        </tr>
    </table>
@if($component->location()->exists())
    <table class="table" width="100%">
        <tr style="background-color: #454777; padding: 10px; color: #fff;"><td>Location Information</td></tr>
        @if($component->location->photo()->exists())
        <tr>
            <td class="text-center p-1">
                <div style="width: 150px; overflow:hidden; margin-left: auto; margin-right: auto;">
                    <img src="{{ asset($component->location->photo->path)}}" width="100%" title="{{ $component->location->name}}">
                </div>
            </td>
        </tr>
        @endif
        <tr><td style="color: {{$component->location->icon}};"><strong>{{ $component->location->name }}</strong></td></tr>
        <tr><td>{{ $component->location->address_1 }}</strong></td></tr>
        @if($component->location->address_2 != "")
        <tr><td>{{ $component->location->address_2 }}</strong></td></tr>
        @endif
        <tr><td>{{ $component->location->city }}</td></tr>
        <tr><td>{{ $component->location->county }}</td></tr>
        <tr><td>{{ $component->location->postcode }}</td></tr>
    </table>
@endif

@if($component->comment()->exists())
    <div class="page-break"></div>
    <table class="table">
        <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;"><th>Comments</th></tr>    
        </thead>                      
        <tbody>
            
            @foreach($component->comment as $comment)
            <tr>
                <td class="text-left"><strong>{{$comment->title}}</strong><br>{{ $comment->comment }}<br><span class="text-info">{{ $comment->user->name }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $comment->created_at, 'Europe/London');}}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif
@if($component->logs()->exists())
    <div class="page-break"></div>
    <table class="table">
        <thead>
            <tr style="background-color: #454777; padding: 10px; color: #fff;"><th>Recent Actvity</th></tr>    
        </thead>                      
        <tbody>
            @foreach($component->logs()->orderBy('created_at', 'desc')->take(30)->get() as $log)
            <tr>
                <td class="text-left">{{$log->data}}<br><span class="text-info">{{ $log->user->name }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $log->created_at, 'Europe/London');}}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection