@extends('layouts.email')

@section('welcome', 'Broadband Expiry')

@section('image')

    <img align="center" border="0" src="{{ asset('images\svg\delete.svg')}}" alt="Apollo Dispose Asset" title="Password"
         style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 100%;max-width: 460px;"
         width="460"/>
@endsection

@section('title')
    {{ $broadband->name }} | Expires in {{$days}} Day's!
@endsection

@section('content')
    <p style="font-size: 14px; line-height: 160%;">
    <span style="font-size: 18px; line-height: 28.8px;">
     This Email is to warn you that {{$broadband->name ?? ' Your Broadband' . ' For ' . $broadband->location->name}} is about to expire!
       This will expire in:
    </span>
        <span style="font-size: 24px; line-height: 35px">{{$days}} Day's</span>
        <span style="font-size: 18px; line-height: 28.8px;">
     Please Create a new renewal date or add a new broadband to the system.
            If you have already resolved this request you can ignore this email.
    </span>
    </p>
@endsection
