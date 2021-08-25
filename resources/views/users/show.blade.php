@extends('layouts.app')

@section('title', 'View '.$user->name)

@section('css')
<link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">View User</h1>
    <div>
        <form id="form{{$user->id}}" action="{{ route('users.destroy', $user->id) }}"
            method="POST">
        <a href="{{ route('users.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                class="fas fa-chevron-left fa-sm text-white-50"></i> Back</a>
        <a href="#"
            class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm deleteBtn" data-id="{{$user->id}}"><i
                class="fas fa-trash fa-sm text-white-50 "></i> Delete</a>
                @csrf
            @method('DELETE')
        <a href="{{ route('users.edit', $user->id)}}"
            class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Edit</a>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
        </form>
    </div>
</div>

@if(session('danger_message'))
<div class="alert alert-danger"> {{ session('danger_message')}} </div>
@endif

@if(session('success_message'))
<div class="alert alert-success"> {{ session('success_message')}} </div>
@endif

<section>
    <p class="mb-4">Information regarding {{ $user->name }}, the assets that are currently assigned to the location
        and any request information.</p>

    <div class="row auto-width m-auto">
        <div class="col-12 mb-4">
            <div class="card shadow h-100 pb-2">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">User Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-3 ">
                            @if($user->photo->exists())
                                <img src="{{$user->photo->path ?? ''}}" alt="{{ $user->name.' Profile Image'}}" class="img-responsive"> 
                            @else
                                <img src="{{ asset('images\profile.png')}}" alt="{{ $user->name.' Profile Image'}}" class="img-responsive" width="100%">
                            @endif
                        </div>
                        <div class="col-12 col-md-6 col-lg-9">
                            <h3>{{ $user->name }}</h3>
                            @php
                                switch($user->role_id){
                                    case 0:
                                        echo '<small class="rounded p-1 m-1 mb-2 bg-danger text-white d-inline-block pointer" data-toggle="tooltip" data-html="true" data-placement="right" title="No Access Permitted">No Access</small>';
                                        break;
                                    case 1:
                                        echo '<small class="rounded p-1 m-1 mb-2 bg-primary text-white d-inline-block pointer" data-toggle="tooltip" data-html="true" data-placement="right" title="Full Control:<br>Full User Permissions<br>Full Location Permissions">Super Admin</small>';
                                        break;
                                    case 2:
                                        echo '<small class="rounded p-1 m-1 mb-2 bg-info text-white d-inline-block pointer" data-toggle="tooltip" data-html="true" data-placement="right" title="Administrator:<br>Location Based User Permissions<br>Set Location Permissions">Administrator</small>';
                                        break;
                                    case 3:
                                        echo '<small class="rounded p-1 m-1 mb-2 bg-success text-white d-inline-block pointer" data-toggle="tooltip" data-html="true" data-placement="right" title="User Manager:<br>No User Permissions<br>Location Based Permissions">User Manager</small>';
                                        break;
                                    case 4:
                                        echo '<small class="rounded p-1 m-1 mb-2 bg-secondary text-white d-inline-block pointer" data-toggle="tooltip" data-html="true" data-placement="right" title="User:<br>No User Permissions<br>View Only - Assets">User</small>';
                                        break;
                                }    
            
                                @endphp
                            
                            <p>
                            @if($user->email != "")
                                <a href="mailto:{{$user->email}}">{{$user->email}}</a><br>
                            @endif
                            @if($user->telephone != "")
                                {{$user->telephone}}</p>
                            @endif
                            @if($location)
                            <p>{{$location->name}}<br>
                                {{ $location->address_1 }}<br>
                                @if($location->address_2 != "")
                                {{ $location->address_2 }}<br>
                                @endif
                                {{ $location->city }}<br>
                                {{ $location->postcode }}
                            </p>
                            @endif
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="card shadow h-100 pb-2">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">Permissions</h6>
                </div>
                <div class="card-body">
                    @php 
                                if($user->role_id == 1){
                                    $locations = App\Models\Location::all();
                                }else{
                                    $locations = $user->locations;
                                }
                                @endphp
                                @foreach($locations as $location)
                                <small data-toggle="tooltip" data-html="true" data-placement="left" title="{{ $location->name }}<br>{{ $location->address1}}" class="rounded p-1 m-1 mb-2 text-white d-inline-block pointer" style="background-color: {{$location->icon}}">{{$location->name}}</small>
                                @endforeach
                </div>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" data-toggle="collapse" data-target="#changes" aria-expanded="false" aria-controls="changes">
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
                            @php($logs = $user->logs()->orderBy('created_at', 'desc')->get())
                            @foreach($logs as $log)
                            <tr>
                                <td class="text-center">{{ $log->id }}</td>
                                <td class="text-left">{{$log->data}}</td>
                                <td class="text-left">{{ $log->user->name ?? 'Unkown'}}</td>
                                <td class="text-right" data-sort="{{ strtotime($log->created_at)}}">{{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y h:i:s')}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" data-toggle="collapse" data-target="#activity" aria-expanded="false" aria-controls="activity">
                    <h6 class="m-0 font-weight-bold">Recent Activity</h6>
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
                            @php($activities = $user->activity()->orderBy('id', 'desc')->get())
                            @foreach($activities as $activity)
                            <tr>
                                <td>{{ $activity->id }}</td>
                                <td class="text-left">{{$activity->loggable_type}}</td>
                                <td class="text-left">{{ $activity->data }}</td>
                                <td class="text-left" data-sort="{{ strtotime($activity->created_at)}}">{{ \Carbon\Carbon::parse($activity->created_at)->format('d-m-Y h:i:s')}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection

@section('modals')

<!-- User Delete Modal-->
<div class="modal fade bd-example-modal-lg" id="removeUserModal" tabindex="-1" role="dialog"
    aria-labelledby="removeUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeUserModalLabel">Are you sure you want to delete this User?
                </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <input id="user-id" type="hidden" value="">
                <p>Select "Delete" to remove this User from the system.</p>
                <small class="text-danger">**Warning this is permanent. All user information will be lost</small>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-danger" type="button" id="confirmBtn">Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    $('.deleteBtn').click(function (e) {
        e.preventDefault();
        $('#user-id').val($(this).data('id'))
        //showModal
        $('#removeUserModal').modal('show')
    });

    $('#confirmBtn').click(function () {
        var form = '#' + 'form' + $('#user-id').val();
        $(form).submit();
    });
</script>
<script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready( function () {
        $('table.logs').DataTable({
                "autoWidth": false,
                "pageLength": 10,
                "columnDefs": [ {
                    "targets": [1,2],
                    "orderable": false
                    }],
                "order": [[ 3, "desc"]],
            });
    } );
</script>

@endsection
