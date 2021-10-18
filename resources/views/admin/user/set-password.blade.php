@extends('layouts.email')

@section('welcome', 'Welcome')

@section('image')

    <img align="center" border="0"
        src="{{ asset('images\svg\undraw_authentication.svg')}}" alt="Apollo Authentication"
        title="Password"
        style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 100%;max-width: 460px;"
        width="460" />
@endsection

 @section('title')
 {{ $user->name }} | Your Apollo Password
 @endsection

 @section('content')
<p style="font-size: 14px; line-height: 160%;">
    <span style="font-size: 18px; line-height: 28.8px;">
        You have successfully created an Apollo Account with your 365 Login Information.
        Your password has be set as:
    </span>
    <span style="font-size: 24px; line-height: 35px">{{ $password }}</span>
    <span style="font-size: 18px; line-height: 28.8px;">
        Please login to your account and change this password. Please note this does not
        change your Microsoft 365 password and you can continue to login with this method in future.
    </span>
</p>
 @endsection