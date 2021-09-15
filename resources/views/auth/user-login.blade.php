@extends('layouts.guest')

@section('css')
    <link href="{{ asset('css/login.css') }}" rel="stylesheet" type="text/css"/>
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
                            <form action="/login" method="POST" class="d-inline">
                                @csrf
                                <label class=" fa-sm text-white-50">Email: </label>
                                <input class="form-control margin-10-bottom input-styles input-style-design" id="email" type="email"
                                       name="email" placeholder="Apollo@clpt.co.uk" required>
                                <label class=" fa-sm text-white-50">Password: </label>
                                <input class="form-control input-styles input-style-design" id="password" type="password"
                                       name="password" placeholder="Password" required>

{{--                                    <div class="alert alert-danger mt-1">This email doesn't belong to this application</div>--}}


                                @if(session('success_message'))
                                    <div class="alert alert-success"> {!! session('success_message')!!} </div>
                                @endif
                                <button class="centred button margin-25-height font20 w-100 mt-4 border-0"> Log in</button>
                            </form>
                        </div>
                    </div>
                    <div class="centred  margin-25-height font20 link-text">
                        <a href="/forgot-password" class="mw-100 d-block text-center button p-2 mt-4 font20">
                            Forgot Password?
                        </a>
                    </div>
                    <div class="flex flex-size margin-centre">
                        <a href="/" class="centred pt-2 text-white-50 d-none d-sm-inline-block">
                            <i class="fas fa-arrow-circle-left fa-sm text-white-50 "></i> Back to Log in Page
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
