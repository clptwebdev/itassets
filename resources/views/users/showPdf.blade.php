@extends('layouts.pdf-reports')

@section('title', 'User Report')

@section('page', $user->name)

@section('user', $admin->name)

@section('content')
<table class="table table-bordered p-1 mb-4" width="100%">
        <tr style="background-color: #454777; padding: 10px; color: #fff;">
            <th colspan="2">User Information</th>
        </tr>
    <tr>
        <td width="15%">
            @if($photo = \App\Models\Photo::find($user->photo_id))
                <img src="{{asset($photo->path)}}"  width="100%" /> 
            @else
                <img src="{{ asset('images/profile.png')}}" alt="{{ $user->name.' Profile Image'}}" width="100%"/>
            @endif
        </td>
        <td>
            <h3>{{ $user->name }}</h3>
            @php
                switch($user->role_id){
                    case 0:
                        echo '<small class="text-danger">No Access</small>';
                        break;
                    case 1:
                        echo '<small class="text-info">Super Admin</small>';
                        break;
                    case 2:
                        echo '<small class="text-warning">Administrator</small>';
                        break;
                    case 3:
                        echo '<small class="text-success">User Manager</small>';
                        break;
                    case 4:
                        echo '<small class="text-secondary">User</small>';
                        break;
                }
            @endphp
            <p>
            @if($user->email != "")
                {{$user->email}}<br>
            @endif
            @if($user->telephone != 0)
                {{$user->telephone}}</p>
            @endif
            @if($location = \App\Models\Location::find($user->location_id))
            <p>{{$location->name}}<br>
                {{ $location->address_1 }}<br>
                @if($location->address_2 != "")
                {{ $location->address_2 }}<br>
                @endif
                {{ $location->city }}<br>
                {{ $location->postcode }}
            </p>
            @endif
        </td>
    </tr>
</table>
<hr>
<table class="table  table-bordered p-1 mb-4" width="100%">
    <thead>
        <tr style="background-color: #454777; padding: 10px; color: #fff;">
            <th>Permissions:</th>
        </tr>
    </thead>
    <tr>
        <td class="text-center align-top">
            @php
                if($user->role_id == 1){
                    $locations = App\Models\Location::all();
                }else{
                    $locations = $user->locations;
                }
                @endphp
                @foreach($locations as $location)
                <span style="background-color: {{$location->icon}};" class="shadow p-2 m-2">{{$location->name}}</span>
                @endforeach
        </td>
    </tr>
</table>

@if(count($user->activity) !=0)
<div class="page-break"></div>
<table class="logs table table-striped ">
    <thead>
        <tr style="background-color: #454777; padding: 10px; color: #fff;">
            <th colspan="4">User Activity</th>
        </tr>
        <tr>
            <th class="text-center" width="10%">Log ID</th>
            <th class="text-center" width="10%">Type</th>
            <th class="textcenter" width="60%">Data</th>
            <th class="text-center" width="20%">Date</th>
        </tr>
    </thead>
    
    <tbody>
            @foreach($user->activity as $activity)
            <tr>
                <td>{{ $activity->id ?? 'NA'}}</td>
                <td class="text-left">{{$activity->loggable_type ?? 'NA'}}</td>
                <td class="text-left">{{ $activity->data  ?? 'NA'}}</td>
                <td class="text-left" >{{ \Carbon\Carbon::parse($activity->created_at)->format('d-m-Y h:i:s')  ?? 'NA'}}</td>
            </tr>
            @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>Log ID</th>
            <th class="text-center">Data</th>
            <th class="text-center">User</th>
            <th class="text-center">Date</th>
        </tr>
    </tfoot>
</table>
@endif
@if(count($user->logs) != 0)
<div class="page-break"></div>
<table class="logs table table-striped ">
    <thead>
        <tr style="background-color: #454777; padding: 10px; color: #fff;">
            <th colspan="4">User Logs</th>
        </tr>
        <tr>
            <th class="text-center" width="10%">Log ID</th>
            <th class="text-center" width="60%">Data</th>
            <th class="textcenter" width="10%">User</th>
            <th class="text-center" width="20%">Date</th>
        </tr>
    </thead>
    
    <tbody>
        @foreach($user->logs as $log)
        <tr>
            <td class="text-center" width="10%">{{ $log->id }}</td>
            <td class="text-left" width="60%">{{$log->data}}</td>
            <td class="text-left" width="10%">{{ $log->user->name ?? 'Unknown'}}</td>
            <td class="text-right" width="20%">{{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y h:i:s')}}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>Log ID</th>
            <th class="text-center">Data</th>
            <th class="text-center">User</th>
            <th class="text-center">Date</th>
        </tr>
    </tfoot>
</table>
@endif
@endsection
