@extends('layouts.pdf-reports')

@section('title', 'Users  Report')

@section('page', 'Users')

@section('user', $user->name)

@section('content')
    <table id="assetsTable" width="100%">
        <thead>
        <tr>
            <th width="5%;" class="text-center">ID</th>
            <th width="15%;" class="text-center">Photo</th>
            <th width="15%;">Name</th>
            <th width="15%;">Email</th>
            <th width="10%;" class="text-center">Role</th>
            <th width="40%;" class="text-center">Permissions</th>
        </tr>
        </thead>

        <tbody>
        @foreach($users as $admin)
            <tr>
                <td class="text-center">{{ $admin->id }}</small></td>
                <td class="text-center">
                    @if($photo = \App\Models\Photo::find($admin->photo_id))
                        <img src="{{asset($photo->path)}}" width="50px"/>
                    @else
                        <img src="{{ asset('images/profile.png')}}" alt="{{ $admin->name.' Profile Image'}}"
                             width="50px"/>
                    @endif
                </td>
                <td>
                    {{ $admin->name}}
                    <br><small>@if($location = \App\Models\Location::find($admin->location_id)){{ $location->name }}@else {{ 'Central Learning Partnership Trust'}}@endif</small>
                </td>
                <td class="text-left">{{ $admin->email ?? 'N/A' }}</td>
                <td class="text-center">
                    @php
                        {{$admin->role->name ?? 'No Role Found'; }}
                    @endphp
                </td>
                <td class="text-center align-top">
                    @php
                        $locations = $user->locations;
                    @endphp
                    @foreach($locations as $location)
                        <small style="color: {{$location->icon}}">{{$location->name}}</small>
                    @endforeach
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th width="5%;">ID</th>
            <th width="15%;">Photo</th>
            <th width="15%;">Name</th>
            <th width="15%;">Email</th>
            <th width="10%;">Role</th>
            <th width="40%;">Permissions</th>
        </tr>
        </tfoot>
    </table>
@endsection
