@extends('layouts.app')

@section('title', 'Update Details')

@section('css')

@endsection

@section('content')
    <form action="{{ route('user.update')}}" method="POST">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit My Details</h1>

            <div class="mt-4 mt-md-0">
                @can('viewAll'  ,\App\Models\User::class)
                    <a href="{{ route('users.index')}}" class="d-inline-block btn btn-sm btn-secondary shadow-sm"><i
                            class="fas fa-chevron-left fa-sm text-white-50"></i> Back to Users</a>
                @endcan

                <button type="submit" class="d-inline-block btn btn-sm btn-success shadow-sm"><i
                        class="far fa-save fa-sm text-white-50"></i> Save
                </button>
                <a data-bs-toggle='modal' data-bs-target='#passwordResetModal'
                   class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm"><i
                        class="fas fa-download fa-sm text-white-50 fa-text-width"></i> Change Password</a>
            </div>
        </div>
        @if(session('danger_message'))
            <div class="alert alert-danger"> {!! session('danger_message')!!} </div>
        @endif

        @if(session('success_message'))
            <div class="alert alert-success"> {!! session('success_message')!!} </div>
        @endif
        <section>
            <p class="mb-4">Adding a new Asset to the asset management system. Enter the following information and
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
                                    <img id="profileImage" src="{{ asset($path)}}" width="100%"
                                         alt="Select Profile Picture">
                                </div>

                                <input type="hidden" id="photo_id" name="photo_id"
                                       value="{{ auth()->user()->photo_id ?? 0}}">
                            </div>

                            <div class="form-group">
                                <label for="name">Name</label><span class="text-danger">*</span>
                                <input type="text"
                                       class="form-control <?php use Illuminate\Support\Facades\Crypt;if ($errors->has('name')) {?>border-danger<?php }?>"
                                       name="name" id="name" placeholder=""
                                       value="{{ old('name') ?? auth()->user()->name}}">
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address</label><span class="text-danger">*</span>
                                <input type="text"
                                       class="form-control <?php if ($errors->has('email')) {?>border-danger<?php }?>"
                                       name="email" id="email" placeholder=""
                                       value="{{ old('email') ?? auth()->user()->email}}">
                            </div>
                            <div class="form-group">
                                <label for="email">Manager</label><span class="text-danger"></span>
                                <input type="text"
                                       class="form-control bg-gray-200 <?php if ($errors->has('manager_id')) {?>border-danger<?php }?>"
                                       name="email" id="email" placeholder="" disabled
                                       value="{{ old('manager_id') ?? auth()->user()->manager->name ?? 'No Manager'}}">
                            </div>

                            <button class="btn btn-lg btn-info">Change</button>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
    {{--    //permissions and activity--}}
    <div class="col-12 mb-4 pt-2 p-r5 p-l5">
        <div class="card shadow h-100 pb-2">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold">Permissions</h6>
            </div>
            <div class="card-body">
                @php
                    $locations = auth()->user()->locations;
                @endphp
                @foreach($locations as $location)
                    <small data-bs-toggle="tooltip" data-html="true" data-bs-placement="left"
                           title="{{ $location->name }}<br>{{ $location->address1}}"
                           class="rounded p-1 m-1 mb-2 text-white d-inline-block pointer"
                           style="background-color: {{$location->icon}}">{{$location->name}}</small>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-12 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                 data-bs-toggle="collapse" data-bs-target="#changes" aria-expanded="false" aria-controls="changes">
                <h6 class="m-0 font-weight-bold">Account Changes</h6>
            </div>
            <div class="card-body collapse" id="changes">
                <table class="logs table table-striped ">
                    <thead>
                    <tr>
                        <th class="col-1">Log ID</th>
                        <th class="col-7 text-center">Data</th>
                        <th class="col-2    text-center">User</th>
                        <th class="col-2 text-center">Date</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Log ID</th>
                        <th class="text-center">Data</th>
                        <th class="text-center">User</th>
                        <th class="text-center">Date</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @php($logs = auth()->user()->logs()->orderBy('created_at', 'desc')->get())
                    @foreach($logs as $log)
                        <tr>
                            <td class="text-center">{{ $log->id }}</td>
                            <td class="text-left">{{$log->data}}</td>
                            <td class="text-left">{{ $log->user->name ?? 'Unkown'}}</td>
                            <td class="text-right"
                                data-sort="{{ strtotime($log->created_at)}}">{{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y h:i:s')}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                 data-bs-toggle="collapse" data-bs-target="#activity" aria-expanded="false" aria-controls="activity">
                <h6 class="m-0 font-weight-bold"><a id="Recent">Recent Activity</a></h6>
            </div>
            <div class="card-body collapse" id="activity">
                <table class="logs table table-striped">
                    <thead>
                    <tr>
                        <th class="col-1">Log ID</th>
                        <th class="col-1 text-center">Type</th>
                        <th class="col-7 text-center">Data</th>
                        <th class="col-3 text-center">Date</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Log ID</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">Data</th>
                        <th class="text-center">Date</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @php($activities = auth()->user()->activity()->orderBy('id', 'desc')->get())
                    @foreach($activities as $activity)
                        <tr>
                            <td>{{ $activity->id }}</td>
                            <td class="text-left">{{$activity->loggable_type}}</td>
                            <td class="text-left">{{ $activity->data }}</td>
                            <td class="text-left"
                                data-sort="{{ strtotime($activity->created_at)}}">{{ \Carbon\Carbon::parse($activity->created_at)->format('d-m-Y h:i:s')}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <x-modals.photo-upload/>
    <x-modals.photo-upload-form/>


    <!-- Reset Password-->
    <div class="modal fade bd-example-modal-lg" id="passwordResetModal" tabindex="-1" role="dialog"
         aria-labelledby="passwordResetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordResetModalLabel">Password Reset</h5>
                    <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{route("change.password.store")}}" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        {{--//current Password--}}

                        <p>Please Enter your current password</p>
                        <input id="oldPassword" class="form-control" name="oldPassword" type="text"
                               placeholder="Your Current Password..." required>
                        {{--//new password--}}

                        <label> Please Enter your New password</label>
                        <input id="newFirstPassword" class="form-control" name="newPassword" type="password"
                               placeholder="Password123" required>
                        {{--//confirm password--}}

                        <label> Please Confirm your New password identically</label>
                        <input id="confirmNewPassword" class="form-control" name="confirmNewPassword" type="password"
                               placeholder="Re-Enter the above" required>
                        {{--//alert--}}
                        <div class="alert alert-info d-none m-2"></div>
                        <div id="messages" style="white-space:pre;"></div>
                    </div>
                    <div class="modal-footer">
                        @if(session('import-error'))
                            <div class="alert text-warning ml-0"> {{ session('import-error')}} </div>
                        @endif
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" type="button" id="confirmBtnImport">
                            Save
                        </button>
                    @csrf
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset('js/photo.js')}}"></script>
    <script>

        //validation for resetting passwords
        var input = document.querySelector('#confirmNewPassword');
        var firstInput = document.querySelector('#newFirstPassword');
        var oldPasswordInput = document.querySelector('#oldPassword');
        var messages = document.querySelector('#messages');
        var messagesOld = document.querySelector('#messagesOld');
        var match = '<div class="alert alert-success mt-2">Passwords Do match</div>'
        var notMatch = '<div class="alert alert-danger mt-2">Passwords Do not match</div>'


        input.addEventListener('input', function () {

            if (input.value === firstInput.value) {
                messages.innerHTML = match;
            } else {
                messages.innerHTML = notMatch;
            }

        });
        firstInput.addEventListener('input', function () {

            if (input.value === firstInput.value) {
                messages.innerHTML = match;
            } else {

                messages.innerHTML = notMatch;
            }

        });
    </script>

@endsection
