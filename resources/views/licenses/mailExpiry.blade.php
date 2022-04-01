@extends('layouts.email')

@section('welcome', 'License Expiry')

@section('image')

    <img align="center" border="0" src="{{ asset('images\svg\delete.svg')}}" alt="Apollo Dispose Asset" title="Password"
         style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 100%;max-width: 460px;"
         width="460"/>
@endsection

@section('title')
    {{ $license->name }} | Expires in {{$days}} Day's!
@endsection

@section('content')
    <p style="font-size: 14px; line-height: 160%;">
    <span style="font-size: 18px; line-height: 28.8px;">
     This Email is to warn you that {{$license->name ?? ' Your Broadband' . ' For ' . $license->location->name}} is about to expire!
       This will expire in:
    </span>
        <span style="font-size: 24px; line-height: 35px">{{$days}} Day's</span>
        <span style="font-size: 18px; line-height: 28.8px;">
     Please Create a new renewal date or add a new License to the system.
            If you have already resolved this request you can ignore this email.
    </span>
        <a class='text-center text-decoration-none' href='{{route('licenses.edit',$license->id)}}'>Click to edit this
                                                                                                   License.</a>
    </p>
@endsection
