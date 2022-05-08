@extends('layouts.app')

@section('title', 'Update Password')

@section('css')

@endsection

@section('content')
    <form action="{{ route('user.update')}}" method="POST">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Change your Password</h1>

            <div class="mt-4 mt-md-0">
                <a href="{{ route('users.index')}}" class="d-inline-block btn btn-sm btn-secondary shadow-sm"><i
                        class="fas fa-chevron-left fa-sm text-white-50"></i> Back to Users</a>
                <button type="submit" class="d-inline-block btn btn-sm btn-success shadow-sm"><i
                        class="far fa-save fa-sm text-white-50"></i> Save
                </button>
            </div>
        </div>

        <section>
            <p class="mb-4">Adding a new Asset to the asset management system. Enter in the following information and
                            click
                            the 'Save' button. Or click the 'Back' button
                            to return the Assets page. </p>
            <div class="row row-eq-height auto-width m-auto">
                <div class="col-12">
                    <div class="card shadow h-100">
                        <div class="card-body">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @csrf

                            <h3 class="h6 text-center mb-3">User Information</h3>

                            <div class="formgroup mb-2 p-2">
                                <div class="m-auto p-2 border border-secondary" style="max-width: 250px;">
                                    @php
                                        if(auth()->user()->photo()->exists()){
                                            $path = auth()->user()->photo->path;
                                        }else{
                                            $path = 'images/profile.png';
                                        }
                                    @endphp
                                    <img id="profileImage" onclick='getPhotoPage(1)' src="{{ asset($path)}}"
                                         width="100%" alt="Select Profile Picture" data-bs-toggle="modal"
                                         data-bs-target="#imgModal">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name">Name</label><span class="text-danger">*</span>
                                <input type="text"
                                       class="form-control <?php if ($errors->has('name')) {?>border-danger<?php }?>"
                                       name="name" id="name" placeholder=""
                                       value="{{ old('name') ?? auth()->user()->name}}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="old_password">Old Password</label><span class="text-danger">*</span>
                                <input type="password"
                                       class="form-control <?php if ($errors->has('old_password')) {?>border-danger<?php }?>"
                                       name="old_password" id="old_password" placeholder="" value="">
                            </div>

                            <div class="form-group">
                                <label for="new_password">New Password</label><span class="text-danger">*</span>
                                <input type="password"
                                       class="form-control <?php if ($errors->has('old_password')) {?>border-danger<?php }?>"
                                       name="new_password" id="new_password" placeholder="" value="">
                            </div>

                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label><span class="text-danger">*</span>
                                <input type="password"
                                       class="form-control <?php if ($errors->has('confirm_password')) {?>border-danger<?php }?>"
                                       name="confirm_password" id="confirm_password" placeholder="" value="">
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
@endsection

@section('modals')
    <x-modals.photo-upload/>
    <x-modals.photo-upload-form/>
@endsection

@section('js')
    <script src="{{asset('js/photo.js')}}"></script>

@endsection
