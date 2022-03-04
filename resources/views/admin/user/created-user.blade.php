@extends('layouts.email')

@section('welcome', 'New User')

@section('image')

    <img align="center" border="0" src="{{ asset('images\svg\create.svg')}}" alt="Apollo | Created user"
         title="Created user"
         style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 100%;max-width: 460px;"
         width="460"/>
@endsection

@section('title')
    {{ $user->name }} has created user {{ $newUser->name}}
@endsection

@section('content')
    <p>This message is to alert you that {{ $user->name }} has created {{ $newUser->name}} on the Apollo Management
       System</p>

    <p>{{ $newUser->name }} has been set to {{ $user->role->name}} and has permissions at the following schools:</p>
    <ul>
        @foreach($newUser->locations as $location)
            <li>{{ $location->name }}</li>
        @endforeach
    </ul>
    <p>If this action wasn't permitted, please contact a member of the web and systems development team</p>
@endsection
