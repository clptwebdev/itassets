@extends('layouts.email')

@section('welcome', 'User Removal')

@section('image')

    <img align="center" border="0"
        src="{{ asset('images\svg\delete.svg')}}" alt="Apollo Authentication"
        title="Password"
        style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 100%;max-width: 460px;"
        width="460" />
@endsection

 @section('title')
 {{ $user->name }} has removed {{ $name}}
 @endsection

 @section('content')
<p>This message is to alert you that {{ $user->name }} has removed {{ $name}} from the Apollo Management System</p>
 @endsection