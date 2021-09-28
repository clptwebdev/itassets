@extends('layouts.app')
@section('title', 'Edit User')
@section('css')

@endsection

@section('content')
    <form action="{{ route('users.update', $user->id)}}" method="POST">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit User</h1>

            <div>
                <a href="{{ route('users.index')}}"
                   class="d-inline-block btn btn-sm btn-grey shadow-sm"><i
                        class="fas fa-chevron-left fa-sm text-white-50"></i> Back to Users</a>
                <button type="submit" class="d-inline-block btn btn-sm btn-green shadow-sm"><i
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
                            {{ method_field('PATCH') }}

                            <h3 class="h6 text-center mb-3">User Information</h3>

                            <div class="form-group">
                                <label for="name">Name</label><span class="text-danger">*</span>
                                <input type="text" class="form-control <?php if ($errors->has('name')) {?>border-danger<?php }?>" name="name"
                                    id="name" placeholder="" value="@if(old('name') !== NULL){{ old('name')}}@else{{$user->name}}@endif">
                            </div>

                            <div class="form-group">
                                <label for="telephone">Telephone</label><span class="text-danger">* (Please use 01 Format)</span>
                                <input type="text" class="form-control <?php if ($errors->has('telephone')) {?>border-danger<?php }?>" name="telephone"
                                    id="telephone" placeholder="" value="@if(old('telephone') !== NULL){{ old('telephone')}}@else{{$user->telephone}}@endif">
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address</label><span class="text-danger">*</span>
                                <input type="text" class="form-control <?php if ($errors->has('email')) {?>border-danger<?php }?>" name="email"
                                    id="email" placeholder="" value="@if(old('email') !== NULL){{ old('email')}}@else{{$user->email}}@endif">
                            </div>

                            <div class="form-group">
                                <label for="location_id">Location</label><span class="text-danger">*</span>
                                <select type="text"
                                    class="form-control mb-3 <?php if ($errors->has('location_id')) {?>border-danger<?php }?>"
                                    name="location_id" id="location_id">
                                    <option value="0" @if(old('location_id') == 0){{'selected'}}@elseif($user->location_id == 0){{ 'selected'}}@endif>Please select a Location</option>
                                    @foreach($locations as $location)
                                    <option value="{{$location->id}}" @if(old('location_id') == $location->id){{'selected'}}@elseif($user->location_id == $location->id){{ 'selected'}}@endif>{{$location->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="role">Role</label><span class="text-danger">*</span>
                                <select type="text"
                                    class="form-control mb-3 <?php if ($errors->has('role')) {?>border-danger<?php }?>"
                                    name="role_id" id="role_id" onchange="javascript:rolePermissions(this, '{{ implode(',', $locations->pluck('id')->toArray())}}', '{{ implode(',', $user->locations->pluck('id')->toArray())}}');">
                                    <option value="0" @if(old('role_id') == 0){{'selected'}}@elseif($user->role_id == 0){{ 'selected'}}@endif>No Access</option>
                                    @if(auth()->user()->role_id == 1)
                                    <option value="1" @if(old('role_id') == 1){{'selected'}}@elseif($user->role_id == 1){{ 'selected'}}@endif>Super Administrator</option>
                                    @endif
                                    <option value="2" @if(old('role_id') == 2){{'selected'}}@elseif($user->role_id == 2){{ 'selected'}}@endif>Administrator</option>
                                    <option value="3" @if(old('role_id') == 3){{'selected'}}@elseif($user->role_id == 3){{ 'selected'}}@endif>Technician</option>
                                    <option value="4" @if(old('role_id') == 4){{'selected'}}@elseif($user->role_id == 4){{ 'selected'}}@endif>User Manager</option>
                                    <option value="5" @if(old('role_id') == 5){{'selected'}}@elseif($user->role_id == 5){{ 'selected'}}@endif>User</option>
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
                                <input type="hidden" class="form-control" name="permission_ids"
                                    id="permission_ids" value="{{ implode(',', $user->locations->pluck('id')->toArray())}}" autocomplete="off">
                            </div>

                            <div class="form-inline">

                                <select type="text"
                                    class="form-control mb-2 mr-sm-2"
                                    name="permission_id" id="permission_id">
                                    <option value="0">Please select a Location</option>
                                    @foreach($locations as $location)
                                    <option value="{{$location->id}}" @if(old('location_id') == $location->id){{'selected'}}@endif>{{$location->name}}</option>
                                    @endforeach
                                </select>
                                <a id="submitPermission" class="btn btn-blue mb-2" onclick="javascript:addPermission();">Add</a>
                                <hr>
                                <div class="w-100">
                                <div id="permissions" class="p-2 row">
                                    @foreach($user->locations as $location)
                                    <div class="col-12 col-md-6 col-lg-4 p-2 h-100">
                                        <div class="card h-100">
                                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between h-100">
                                                @if ($location->photo()->exists())
                                                    <img src="{{ asset($location->photo->path) ?? 'null' }}" alt="{{ $location->name}}" height="40px">
                                                @else
                                                    <i class="fas fa-school fa-2x text-gray-300"></i>
                                                @endif
                                                <small style="color:{{$location->icon}}">
                                                    {{ $location->name}}
                                                </small>
                                                <a  href="#" onclick="javascript:removePermission({{$location->id}});" role="button">
                                                    <i class="fas fa-times fa-sm fa-fw text-gray-400"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
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
                getPermissions();
            }

            function rolePermissions(obj, string, original){
                if(obj.value == 1){
                    document.getElementById('permission_ids').value = string;
                    getPermissions();
                }else{
                    document.getElementById('permission_ids').value = original;
                    getPermissions();
                }
            }

            function getPermissions(){
                var permissions = document.getElementById('permission_ids');
                var inputs = permissions.value.split(",");

                var fData = new FormData();
                inputs.forEach(element => {
                    fData.append('ids[]', element);
                });
                var token = $("[name='_token']").val();
                fData.append('_token', token);

                $.ajax({
                    url: '/permissions/users',
                    type: 'POST',
                    data: fData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        document.getElementById("permissions").innerHTML = data;
                    },
                });
            }

            function removePermission(id){
                var permissions = document.getElementById('permission_ids');
                var inputs = permissions.value.split(",");
                const index = inputs.indexOf(id.toString());
                if (index > -1) {
                    inputs.splice(index, 1);
                }
                permissions.value = inputs.join(',');
                getPermissions();
            }

        </script>
    @endsection
