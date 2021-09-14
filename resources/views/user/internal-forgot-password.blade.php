@extends("layouts.guest")
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
                    <h3 class="centred d-block">
                        Resetting your Account Password
                    </h3>
                    <div class="mb-4 text-sm fa-sm text-white-50">
                        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                    </div>
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('forgot.my.password.store') }}">
                    @csrf

                    <!-- Email Address -->
                        <div>
                            <x-label for="email" :value="__('Email')" />

                            <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button>
                                {{ __('Email Password Reset Link') }}
                            </x-button>
                        </div>
                        <div class="flex flex-size margin-centre">
                            <a href="{{route("user.details")}}" class="centred pt-2 text-white-50 d-none d-sm-inline-block">
                                <i class="fas fa-arrow-circle-left fa-sm text-white-50 "></i> Back to User Details Page
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection







