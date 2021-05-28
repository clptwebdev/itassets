@extends('layouts.guest')

@section('css')
<link href="{{ asset('css/admin.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="container">
        <div class="flex direct-middle">
            <div class="container-middle-1  container-margin-login flex-size margin-centre">
                <div class="apollo">
                    <img src="{{ asset('images/apollo-logo.svg')}}">
                </div>
                <div>
                    <h3 class="padding-25-width text-center">Welcome to Apollo</h3>
                </div>
    
            </div>
            <hr class="hr-break">
            <div class="container-middle-2  container-margin-login flex-size">
                <div class="margin-centre">
                    <h3 class="centred">
                        Sign In To Your Account
                    </h3>
                    <div class="centred button padding-25-height font20">
                        <a href="#">Login</a>
                    </div>
                    <div>
                        <div class="centred">
                            <span></span>
                            <h3 class="centred padding-25-height white">OR VIA</h3>
                            <span></span>
                        </div>
                    </div>
                    <a href="/login/microsoft" class="centred button padding-25-height font20">
                            <img src="{{ asset('/images/svg/microsoft.svg')}}" alt="MS" height="30px" style="display:inline;">
                            Office 365
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
