@extends('layouts.app')

@section('title', 'View Roles')

@section('css')

@endsection

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">User Roles</h1>
    <div>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
    </div>
</div>

@if(session('danger_message'))
<div class="alert alert-danger"> {{ session('danger_message')}} </div>
@endif

@if(session('success_message'))
<div class="alert alert-success"> {{ session('success_message')}} </div>
@endif

<section>
    <p class="mb-4">Information regarding the different roles set with the Apollo Asset Management&copy; System</p>
    <div class="row row-eq-height">
        <div class="col-12 col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header">Super Administrators</div>
                <div id="superBody"  class="card-body h-100" >
                    @php($super = \App\Models\User::where('role_id', 1)->get())
                    @foreach($super as $sup)
                    <div id="user{{$sup->id}}" data-id="{{$sup->id}}" class="card  mb-2" draggable="true" ondragstart="drag(event)">
                        <div class="p-1 pointer d-flex flex-row align-items-center justify-content-between">
                            <div class="card-title m-0">
                                @if($sup->photo()->exists())
                                <img src="{{asset($sup->photo->path) ?? asset('images\profile.png')}}" alt="{{$sup->name}}" width="40px">
                                @else
                                <img src="{{asset('images\profile.png')}}" alt="{{$sup->name}}" width="40px"> 
                                @endif
                            
                                {{ $sup->name }}
                            </div>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownUser{{$sup->id}}Link"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                    aria-labelledby="dropdownUser{{$sup->id}}Link">
                                    <div class="dropdown-header">User Options:</div>
                                    <a class="dropdown-item" href="#">View</a>
                                    <a class="dropdown-item" href="#">Edit</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Location Permissions</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div id="superDrop" data-role="1" ondrop="drop(event)" ondragover="allowDrop(event)" class="drop-boxes p-2 border-dashed border-secondary text-center" style="display: none; border: dashed 1px #666;">
                        Drop User Here
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 mb-4">
            <div  class="card shadow h-100">
                <div class="card-header">Administrators</div>
                <div id="adminBody" class="card-body h-100">
                    @php($super = \App\Models\User::where('role_id', 2)->get())
                    @foreach($super as $sup)
                    <div id="user{{$sup->id}}" data-id="{{$sup->id}}" class="card  mb-2" draggable="true" ondragstart="drag(event)">
                        <div class="p-1 pointer d-flex flex-row align-items-center justify-content-between">
                            <div class="card-title m-0">
                                @if($sup->photo()->exists())
                                <img src="{{asset($sup->photo->path) ?? asset('images\profile.png')}}" alt="{{$sup->name}}" width="40px">
                                @else
                                <img src="{{asset('images\profile.png')}}" alt="{{$sup->name}}" width="40px"> 
                                @endif
                            
                                {{ $sup->name }}
                            </div>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownUser{{$sup->id}}Link"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                    aria-labelledby="dropdownUser{{$sup->id}}Link">
                                    <div class="dropdown-header">User Options:</div>
                                    <a class="dropdown-item" href="#">View</a>
                                    <a class="dropdown-item" href="#">Edit</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Location Permissions</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div id="adminDrop" data-role="2" ondrop="drop(event)" ondragover="allowDrop(event)" class="drop-boxes p-2 border-dashed border-secondary text-center" style="display: none; border: dashed 1px #666;">
                        Drop User Here
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-eq-height">
        <div class="col-12 col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header">User Manager</div>
                <div id="managerBody" class="card-body h-100">
                    @php($super = \App\Models\User::where('role_id', 3)->get())
                    @foreach($super as $sup)
                    <div id="user{{$sup->id}}" class="card  mb-2" data-id="{{$sup->id}}" draggable="true" ondragstart="drag(event)">
                        <div class="p-1 pointer d-flex flex-row align-items-center justify-content-between">
                            <div class="card-title m-0">
                                @if($sup->photo()->exists())
                                <img src="{{asset($sup->photo->path) ?? asset('images\profile.png')}}" alt="{{$sup->name}}" width="40px">
                                @else
                                <img src="{{asset('images\profile.png')}}" alt="{{$sup->name}}" width="40px"> 
                                @endif
                            
                                {{ $sup->name }}
                            </div>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownUser{{$sup->id}}Link"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                    aria-labelledby="dropdownUser{{$sup->id}}Link">
                                    <div class="dropdown-header">User Options:</div>
                                    <a class="dropdown-item" href="#">View</a>
                                    <a class="dropdown-item" href="#">Edit</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Location Permissions</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div id="managerDrop" data-role="3" ondrop="drop(event)" ondragover="allowDrop(event)" class="drop-boxes p-2 border-dashed border-secondary text-center" style="display: none; border: dashed 1px #666;">
                        Drop User Here
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header">Users</div>
                <div id="userBody" class="card-body h-100">
                    @php($super = \App\Models\User::where('role_id', 4)->get())
                    @foreach($super as $sup)
                    <div id="user{{$sup->id}}" data-id="{{$sup->id}}" class="card  mb-2" draggable="true" ondragstart="drag(event)">
                        <div class="p-1 pointer d-flex flex-row align-items-center justify-content-between">
                            <div class="card-title m-0">
                                @if($sup->photo()->exists())
                                <img src="{{asset($sup->photo->path) ?? asset('images\profile.png')}}" alt="{{$sup->name}}" width="40px">
                                @else
                                <img src="{{asset('images\profile.png')}}" alt="{{$sup->name}}" width="40px"> 
                                @endif
                            
                                {{ $sup->name }}
                            </div>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownUser{{$sup->id}}Link"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                    aria-labelledby="dropdownUser{{$sup->id}}Link">
                                    <div class="dropdown-header">User Options:</div>
                                    <a class="dropdown-item" href="{{ route('users.show', $user->id) }}">View</a>
                                    <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">Edit</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item permission" data-id="{{$sup->id}}" href="#">Location Permissions</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div id="userDrop" data-role="4" ondrop="drop(event)" ondragover="allowDrop(event)" class="drop-boxes p-2 border-dashed border-secondary text-center" style="display:none; border: dashed 1px #666;">
                        Drop User Here
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</section>

@endsection

@section('modals')
<!-- Delete Modal-->
<div class="modal fade bd-example-modal-lg" id="userPermissionsModal" tabindex="-1" role="dialog"
    aria-labelledby="ruserPermissionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="permissions" class="modal-content">
            
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {
        ev.dataTransfer.setData("text", ev.target.id);
        ev.dataTransfer.setData("id", ev.target.dataset.id);
        $('.drop-boxes').show();
    }

    function drop(ev) {
        ev.preventDefault();
        var data = ev.dataTransfer.getData("text");
        var id = ev.dataTransfer.getData("id");
        ev.target.parentElement.prepend(document.getElementById(data));
        $('.drop-boxes').hide();
    }

    $('.permission').click(function() {
        var id = $(this).data('id')
        $('#user-name').val($(this).data('name'));

        $.ajax({
            url: `/users/${id}/locations`,
            type: 'GET',
            success: function(data) {
                document.getElementById("permissions").innerHTML = data;    
            },
        });

        $('#removeUserModal').modal('show')
    });
</script>
@endsection
