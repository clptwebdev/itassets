@extends('layouts.app')

@section('title', 'View Roles')

@section('css')

@endsection

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Roles</h1>
    </div>
    <x-handlers.alerts/>

    <section>
        <p class="mb-4">Information regarding the different roles set with the Apollo Asset Management&copy; System</p>
        <div class="row row-eq-height">
            @foreach(\App\Models\Role::all() as $role)
                <div class="col-12 col-lg-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header">{{$role->name}}</div>
                        <div id="superBody" class="card-body h-100">
                            @php($users = \App\Models\User::where('role_id', $role->id)->get())
                            @foreach($users as $user)
                                <div id="user{{$user->id}}" data-id="{{$user->id}}" class="card mb-2 user_role"
                                     draggable="true" ondragstart="drag(event)">
                                    <div class="p-1 pointer d-flex flex-row align-items-center justify-content-between">
                                        <div class="card-title m-0">
                                            @if($user->photo()->exists())
                                                <img src="{{asset($user->photo->path) ?? asset('images\profile.png')}}"
                                                     alt="{{$user->name}}" width="40px">
                                            @else
                                                <img src="{{asset('images\profile.png')}}" alt="{{$user->name}}"
                                                     width="40px">
                                            @endif

                                            {{ $user->name }}
                                        </div>
                                        <div class="dropdown no-arrow">
                                            <a class="dropdown-toggle" href="#" role="button"
                                               id="dropdownUser{{$user->id}}Link" data-bs-toggle="dropdown"
                                               aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                                 aria-labelledby="dropdownUser{{$user->id}}Link">
                                                <div class="dropdown-header">User Options:</div>
                                                <a class="dropdown-item"
                                                   href="{{ route('users.show', $user->id)}}">View</a>
                                                @if(auth()->user()->role->significance >= $user->role->significance)
                                                    <a class="dropdown-item" href="{{ route('users.edit', $user->id)}}">Edit</a>
                                                @endcan
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item permission" data-id="{{$user->id}}"
                                                   data-name="{{$user->name}}" href="#">Location Permissions</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div id="superDrop" data-role="{{$role->id}}" ondrop="drop(event)"
                                 ondragover="allowDrop(event)"
                                 class="drop-boxes p-2 border-dashed border-secondary text-center"
                                 style="display: none; border: dashed 1px #666;">
                                Drop User Here
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class='col-12'>
                <div class="card shadow mb-3">
                    <div class="card-body">
                        <h4>Help with User Permissions</h4>
                        <p>Click <a href="{{route("documentation.index").'#collapseTwelvePermissions'}}">here</a> for
                           the
                           Documentation on Users Roles and permissions!</p>
                    </div>
                </div>
            </div>
    </section>

@endsection

@section('modals')
    <!-- Delete Modal-->
    <div class="modal fade bd-example-modal-lg" id="userPermissionsModal" tabindex="-1" role="dialog"
         aria-labelledby="userPermissionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeUserModalLabel"><span id="user_name"></span> has access to the
                                                                                                   following locations.
                    </h5>
                    <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="permissions">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        const dropBoxes = document.querySelectorAll(".drop-boxes");
        const xhttp = new XMLHttpRequest();
        const permissionModal = new bootstrap.Modal(document.getElementById('userPermissionsModal'));

        function allowDrop(ev) {
            ev.preventDefault();
        }

        function drag(ev) {

            ev.dataTransfer.setData("text", ev.target.id);
            ev.dataTransfer.setData("id", ev.target.dataset.id);
            dropBoxes.forEach(function (userItem) {
                userItem.classList.add('d-block');
            });
        }

        function drop(ev) {
            ev.preventDefault();
            let data = ev.dataTransfer.getData("text");
            let id = ev.dataTransfer.getData("id");
            let role = ev.target.dataset.role;
            ev.target.parentElement.prepend(document.getElementById(data));
            dropBoxes.forEach(function (userItem) {
                userItem.classList.remove('d-block');
            });
            xhttp.open("GET", `/users/${id}/role/${role}`);
            xhttp.send();
        }

        document.querySelectorAll(".permission").forEach(elem => elem.addEventListener("click", () => {
            let id = elem.getAttribute('data-id');
            document.getElementById('user_name').innerHTML = elem.getAttribute('data-name');

            xhttp.onload = function () {
                document.getElementById("permissions").innerHTML = xhttp.responseText;
            }
            xhttp.open("GET", `/users/${id}/locations`);
            xhttp.send();
            permissionModal.show();
        }));
    </script>
@endsection
