@extends("layouts.guest")
<Section class="bg-white">
    <x-auth-card>
        <x-slot name="logo" >
            <div class="container-margin-login flex-size margin-centre">
                <div class="apollo" style="max-width: 300px;">
                    <x-application-logo/>
                </div>
                <div class=" w-100 text-center">
                    <h3 class="text-yellow-dash">Welcome to Sothis</h3>
                </div>
                <hr class="hr-break w-50 m-auto">
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />
        <div class="row">
            <form method="POST" action="{{ route('password.update') }}" class="col-8 mx-auto">
            @csrf

            <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="w-75 mx-auto">
                    <x-label for="email" :value="__('Email')" />

                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus />
                </div>

                <!-- Password -->
                <div class="mt-4 w-75 mx-auto">
                    <x-label for="password" :value="__('Password')" />

                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4 w-75 mx-auto">
                    <x-label for="password_confirmation" :value="__('Confirm Password')" />

                    <x-input id="password_confirmation" class="block mt-1 w-full"
                             type="password"
                             name="password_confirmation" required />
                </div>

                <div class="flex items-center justify-end mt-4 ">
                    <x-button>
                        {{ __('Reset Password') }}
                    </x-button>
                </div>
            </form>
        </div>
    </x-auth-card>

</Section>
