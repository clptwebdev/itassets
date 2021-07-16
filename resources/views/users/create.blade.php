@extends('layouts.app')

@section('css')

@endsection

@section('content')
    <form action="{{ route('users.store')}}" method="POST">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Add New User</h1>

            <div>
                <a href="{{ route('users.index')}}"
                   class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
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
                to return the Assets page.
            </p>
            <div class="row row-eq-height container m-auto">
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

                            <div class="form-group">
                                <label for="name">Name</label><span class="text-danger">*</span>
                                <input type="text" class="form-control <?php if ($errors->has('name')) {?>border-danger<?php }?>" name="name"
                                    id="name" placeholder="" value="{{ old('name')}}">
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address</label><span class="text-danger">*</span>
                                <input type="text" class="form-control <?php if ($errors->has('email')) {?>border-danger<?php }?>" name="email"
                                    id="email" placeholder="" value="{{ old('email')}}">
                            </div>

                            <div class="form-group">
                                <label for="location_id">Location</label><span class="text-danger">*</span>
                                <select type="text"
                                    class="form-control mb-3 <?php if ($errors->has('location_id')) {?>border-danger<?php }?>"
                                    name="location_id" id="location_id">
                                    <option value="0" @if(old('location_id') == 0){{'selected'}}@endif>Please select a Location</option>
                                    @foreach($locations as $location)
                                    <option value="{{$location->id}}" @if(old('location_id') == $location->id){{'selected'}}@endif>{{$location->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="role">Role</label><span class="text-danger">*</span>
                                <select type="text"
                                    class="form-control mb-3 <?php if ($errors->has('role')) {?>border-danger<?php }?>"
                                    name="role" id="role">
                                    <option @if(old('role') == 0){{'selected'}}@endif>Please select a role for the user</option>
                                    <option value="1" @if(old('role') == 1){{'selected'}}@endif>Super Administrator</option>
                                    <option value="2" @if(old('role') == 2){{'selected'}}@endif>Administrator</option>
                                    <option value="3" @if(old('role') == 3){{'selected'}}@endif>User Manager</option>
                                    <option value="4" @if(old('role') == 4){{'selected'}}@endif>User</option>
                                </select>
                            </div>
                        </div>
                    </div>                           
                </div>

                <div class="col-12 mt-4">
                    <div class="card shadow">
                        
                        <div class="card-body">
                            <div class="card-title">Permissions</div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="permission_ids"
                                    id="permission_ids"value="">
                            </div>

                            <div class="form-inline">
                                
                                <select type="text"
                                    class="form-control mb-2 mr-sm-2"
                                    name="permission_id" id="permission_id">
                                    <option value="0" @if(old('permission_id') == 0){{'selected'}}@endif>Please select a Location</option>
                                    @foreach($locations as $location)
                                    <option value="{{$location->id}}" @if(old('location_id') == $location->id){{'selected'}}@endif>{{$location->name}}</option>
                                    @endforeach
                                </select>
                                <a id="submitPermission" class="btn btn-primary mb-2" onclick="javascript:addPermission();">Add</a>
                                <small id="passwordHelpBlock" class="form-text text-muted">
                                    Your password must be 8-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.
                                </small>
                                <hr>
                                <div id="permissions" class="p-2">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
    @endsection

    @section('modals')
       
    @endsection

    @section('js')
        <script>
            function addPermission(){
                var permission = document.getElementById('permission_id').value;
                var permissions = document.getElementById('permission_ids');
                var array = permissions.value.split(",");

                if(!array.includes(String(permission))){
                    //Create a New DIV element
                    if(permissions.value != ""){
                        permissions.value +=','+permission;
                    }else{
                        permissions.value = permission;
                    }
                }

                function getPermissions(){
                    var permissions = document.getElementById('permission_ids');
                }
            }
        </script>
    @endsection
