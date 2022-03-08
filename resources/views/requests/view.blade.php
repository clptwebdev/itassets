@extends('layouts.app')

@section('title', 'User Requests')

@section('css')
@endsection

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Requests</h1>
        <div></div>
    </div>

    @if(session('danger_message'))
        <div class="alert alert-danger"> {!! session('danger_message')!!} </div>
    @endif

    @if(session('success_message'))
        <div class="alert alert-success"> {!! session('success_message')!!} </div>
    @endif

    <section>
        <p class="mb-4">Below are the requests made by the Users of the system. These can include requesting access,
                        asset transfer or asset disposal.</p>
        <!-- DataTales Example -->

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive" id="table">
                    <table id="assetsTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-1 text-center"><small>Type</small></th>
                            <th class="col-2"><small>Model</small></th>
                            <th class="col-2"><small>Requested By</small></th>
                            <th class="col-5"><small>Notes</small></th>
                            <th class="col-1 text-center"><small>Date</small></th>
                            <th class="col-1 text-center"><small>Status</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="text-center"><small>Type</small></th>
                            <th><small>Model</small></th>
                            <th><small>Requested By</small></th>
                            <th><small>Notes</small></th>
                            <th class="text-center"><small>Date</small></th>
                            <th class="text-center"><small>Status</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($requests as $request)
                            <tr>
                                <td class="text-center">
                                    @if($request->type == 'disposal')
                                        <i class="fas fa-trash text-coral"></i>
                                    @elseif($request->type == 'transfer')
                                        <i class="fas fa-exchange-alt text-green"></i>
                                    @else
                                        <i class="fas fa-user-plus text-lilac"></i>
                                    @endif
                                </td>
                                <td>
                                    @if($request->type != 'access')
                                        @php
                                            $m = "\\App\\Models\\".ucfirst($request->model_type);
                                            $model = $m::find($request->model_id);
                                        @endphp
                                        {{ $model->name ?? $model->asset_tag ?? 'Unknown Asset' }}
                                    @else
                                        Asset Management System
                                    @endif
                                </td>
                                <td>
                                    @php($user = App\Models\User::find($request->user_id))
                                    {{ $user->name ?? 'Unknown'}}
                                </td>
                                <td><small>{{ $request->notes }}</small></td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($request->date)->format('d-m-Y') }}</td>
                                <td class="text-center">

                                    @if($request->status == 1)
                                        @php($super = App\Models\User::find($request->super_id))
                                        <i class="fas fa-check-circle text-green pointer" data-toggle="tooltip"
                                           data-html="true"
                                           title="Approved by {{$super->name}}<br>{{\Carbon\Carbon::parse($request->updated_at)->format('d M y')}}"></i>
                                    @elseif($request->status == 2)
                                        @php($super = App\Models\User::find($request->super_id))
                                        <i class="fas fa-times-circle text-coral pointer" data-toggle="tooltip"
                                           data-html="true"
                                           title="Denied by {{$super->name}}<br>{{\Carbon\Carbon::parse($request->updated_at)->format('d M y')}}"></i>
                                    @else
                                        @if($request->type != 'access')
                                            <a class="m-1" href="{{ route('request.handle', [$request->id, '1'])}}">
                                                <i class="fas fa-check-circle text-green"></i></a>
                                            <a class="m-1" href="{{ route('request.handle', [$request->id, '2'])}}"><i
                                                    class="fas fa-times-circle text-coral"></i></a>
                                        @else
                                            <a class="m-1 accessBtn" href="#" data-id="{{$request->id}}"
                                               data-name="{{$user->name}}"><i
                                                    class="fas fa-check-circle text-green"></i></a>
                                            <a class="m-1" href="{{ route('request.handle', [$request->id, '2'])}}"><i
                                                    class="fas fa-times-circle text-coral"></i></a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between align-content-center">
                        <div>
                            @if($requests->hasPages())
                                {{ $requests->links()}}
                            @endif
                        </div>
                        <div class="text-right">
                            Showing Requests {{ $requests->firstItem() }} to {{ $requests->lastItem() }}
                            ({{ $requests->total() }} Total Results)
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>

@endsection

@section('modals')
    <!-- Delete Modal-->
    <div class="modal fade bd-example-modal-lg" id="accessModal" tabindex="-1" role="dialog"
         aria-labelledby="accessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{route('request.access.handle')}}" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="accessModalLabel">Allow <span id="userName"></span> access to Apollo
                        </h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input id="request_id" name="request_id" type="hidden" value="">
                        <div class="form-group">
                            <label for="location_id">Location</label><span class="text-danger">*</span>
                            <select type="text"
                                    class="form-control mb-3 <?php if ($errors->has('location_id')) {?>border-danger<?php }?>"
                                    name="location_id" id="location_id">
                                <option value="0" @if(old('location_id') == 0){{'selected'}}@endif>Please select a
                                                                                                   Location
                                </option>
                                @foreach($locations as $location)
                                    <option
                                        value="{{$location->id}}" @if(old('location_id') == $location->id){{'selected'}}@endif>{{$location->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="role">Role</label><span class="text-danger">*</span>
                            {{--                    <select type="text"--}}
                            {{--                        class="form-control mb-3 <?php if ($errors->has('role')) {?>border-danger<?php }?>"--}}
                            {{--                        name="role_id" id="role_id" onchange="javascript:rolePermissions(this, '{{ implode(',', $locations->pluck('id')->toArray())}}');">--}}
                            {{--                        <option @if(old('role_id') == 0){{'selected'}}@endif>Please select a role for the user</option>--}}
                            {{--                        @if(auth()->user()->role_id == 1)--}}
                            {{--                        <option value="1" @if(old('role_id') == 1){{'selected'}}@endif>Super Administrator</option>--}}
                            {{--                        @endif--}}
                            {{--                        <option value="2" @if(old('role_id') == 2){{'selected'}}@endif>Administrator</option>--}}
                            {{--                        <option value="3" @if(old('role_id') == 3){{'selected'}}@endif>Technician</option>--}}
                            {{--                        <option value="4" @if(old('role_id') == 3){{'selected'}}@endif>User Manager</option>--}}
                            {{--                        <option value="5" @if(old('role_id') == 4){{'selected'}}@endif>User</option>--}}
                            {{--                    </select>--}}
                        </div>

                        <div class="card-title">Permissions</div>
                        <div class="form-group">
                            <input type="hidden" class="form-control" name="permission_ids" id="permission_ids" value=""
                                   autocomplete="off">
                        </div>

                        <div class="form-inline">

                            <select type="text" class="form-control mb-2 mr-sm-2" name="permission_id"
                                    id="permission_id">
                                <option value="0" @if(old('permission_id') == 0){{'selected'}}@endif>Please select a
                                                                                                     Location
                                </option>
                                @foreach($locations as $location)
                                    <option
                                        value="{{$location->id}}" @if(old('location_id') == $location->id){{'selected'}}@endif>{{$location->name}}</option>
                                @endforeach
                            </select>
                            <a id="submitPermission" class="btn btn-blue mb-2"
                               onclick="javascript:addPermission();">Add</a>

                            <hr>
                            <div class="w-100">
                                <div id="permissions" class="p-2 row"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-green" type="submit">Allow Permissions</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')

    <script>

        const accessBtn = document.querySelectorAll('.accessBtn');
        const userName = document.querySelector('#userName');
        const requestId = document.querySelector('#request_id');

        accessBtn.forEach(function (item) {
            item.addEventListener('click', function (e) {
                const name = this.getAttribute('data-name');
                const id = this.getAttribute('data-id');

                userName.innerHTML = name;
                requestId.value = id;
                $('#accessModal').modal('show')
            });
        });

        function addPermission() {
            var permission = document.getElementById('permission_id').value;
            var permissions = document.getElementById('permission_ids');
            var array = permissions.value.split(",");

            if (!array.includes(String(permission))) {
                //Create a New DIV element
                if (permissions.value != "") {
                    permissions.value += ',' + permission;
                } else {
                    permissions.value = permission;
                }
            }
            getPermissions();
        }

        function rolePermissions(obj, string) {
            if (obj.value == 1) {
                document.getElementById('permission_ids').value = string;
                getPermissions();
            } else {
                document.getElementById('permission_ids').value = '';
                getPermissions();
            }
        }

        function getPermissions() {
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
                success: function (data) {
                    document.getElementById("permissions").innerHTML = data;
                },
            });
        }

        function removePermission(id) {
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
