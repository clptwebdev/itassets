@extends("layouts.guest")

@section('title', 'Forgot password')

@section('css')
    <link href="{{ asset('css/login.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="container">
        <div class="flex direct-middle">

    <x-auth-card>
        <x-slot name="logo" >
            <div class="container-margin-login flex-size margin-centre">
                <div class="apollo" style="max-width: 300px;">
                    <x-application-logo/>
                </div>
                <div class=" w-100 text-center">
                    <h3 class="text-yellow-dash">Welcome to Apollo</h3>
                </div>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />
<div style="background-color: #474775 ;">
        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                    type="password"
                                    name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Reset Password') }}
                </x-button>
            </div>
        </form>
</div>
    </x-auth-card>
    </div>
    </div>
@endsection
