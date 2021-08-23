@extends('layouts.guest')

@section('css')
<link href="{{ asset('css/login.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="container">
    <div class="flex direct-middle">
        <div class="container-middle-1  container-margin-login flex-size margin-centre">
            <div class="apollo" style="max-width: 300px;">
                <x-application-logo/>
            </div>
            <div class="m-3 w-100">
                <h3 class="padding-25-width text-center">Welcome to Apollo</h3>
            </div>

        </div>
        <hr class="hr-break">
        <div class="container-middle-2  container-margin-login flex-size">
            <div class="margin-centre">
                <h3 class="centred">
                    Sign In To Your Account
                </h3>
                <div>
                    <div class="centred">
                        <form action="{{route('login')}}" method="POST">
                            <div class="form-control">
                                <input class="margin-10-bottom input-styles input-style-design" id="username" type="username" name="username" placeholder="Username" required>
                                <input  class="input-styles input-style-design" id="password" type="password" name="password" placeholder="Password" required>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="centred button margin-25-height font20">
                    <a href="password/reset"> Log in</a>
                </div>
                {{-- <div class="centred button margin-25-height font20">
                    <a href="password/reset"> Forgot password?</a>
                </div> --}}
                <div class="centred  margin-25-height font20 link-text">
                    <a href="/login/microsoft" class=" d-block text-center button padding-25-height font20">
                        <img src="{{ asset('/images/svg/microsoft.svg')}}" alt="MS" height="30px" style="display:inline;">
                        Office 365
                </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
