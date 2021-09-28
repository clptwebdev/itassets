@extends('layouts.app')

@section('title', 'View all Users')

@section('css')
<link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet" />
@endsection

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Users</h1>
    <div class="mt-4 mt-sm-0">
        <a href="{{ route('users.create')}}" class="d-inline-block btn btn-sm btn-green shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Add New User</a>
        @can('viewAll', auth()->user())
            <form class="d-inline-block" action="{{ route('users.pdf')}}" method="POST">
                @csrf
                <input type="hidden" value="{{ json_encode($users->pluck('id'))}}" name="users"/>
            <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm loading"><i
                    class="fas fa-file-pdf fa-sm text-dark-50"></i> Generate Report</button>
            </form>
            @if($users->count() >1)
            <a href="/exportusers" class="d-inline-block btn btn-sm btn-yellow shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50"></i>Export</a>
            @endif
        @endcan
    </div>
</div>

@if(session('danger_message'))
<div class="alert alert-danger"> {!! session('danger_message')!!} </div>
@endif

@if(session('success_message'))
<div class="alert alert-success"> {!! session('success_message')!!} </div>
@endif

<section>
    <p class="mb-4">Below are the different suppliers of the assets stored in the management system. Each has
        different options and locations can created, updated, and deleted.</p>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="usersTable" class="table table-striped w-100">
                    <thead>
                        <tr>
                            <th class="col-1 d-none d-md-table-cell"><small>ID</small></th>
                            <th class="col-1"><small>Name</small></th>
                            <th class="col-2 d-none d-md-table-cell"><small>Email Address</small></th>
                            <th class="col-1 d-none d-md-table-cell"><small>Admin</small></th>
                            <th class="col-5 d-none d-md-table-cell"><small>Permissions</small></th>
                            <th class="text-right col-2"><small>Options</small></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="col-1 d-none d-md-table-cell"><small>ID</small></th>
                            <th class="col-1"><small>Name</small></th>
                            <th class="col-2 d-none d-md-table-cell"><small>Email Address</small></th>
                            <th class="col-1 d-none d-md-table-cell"><small>Admin</small></th>
                            <th class="col-5 d-none d-md-table-cell"><small>Permissions</small></th>
                            <th class="text-right col-2"><small>Options</small></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="text-center d-none d-md-table-cell">{{ $user->id }}</td>
                            <td>
                                {{ $user->name }}
                                <span class="d-block d-md-none">{{ $user->email }}</span>
                            </td>
                            <td class="d-none d-md-table-cell">{{ $user->email }}</td>
                            <td class="text-center d-none d-md-table-cell">
                                @php
                                switch($user->role_id){
                                    case 0:
                                        echo '<small class="rounded p-1 m-1 mb-2 bg-grey text-white d-inline-block pointer" data-toggle="tooltip" data-html="true" data-placement="left" title="No Access Permitted">No Access</small>';
                                        break;
                                    case 1:
                                        echo '<small class="rounded p-1 m-1 mb-2 bg-coral text-white d-inline-block pointer" data-toggle="tooltip" data-html="true" data-placement="left" title="Full Control:<br>Full User Permissions<br>Full Location Permissions">Super Admin</small>';
                                        break;
                                    case 2:
                                        echo '<small class="rounded p-1 m-1 mb-2 bg-blue text-white d-inline-block pointer" data-toggle="tooltip" data-html="true" data-placement="left" title="Administrator:<br>Location Based User Permissions<br>Set Location Permissions">Administrator</small>';
                                        break;
                                    case 3:
                                        echo '<small class="rounded p-1 m-1 mb-2 bg-green text-white d-inline-block pointer" data-toggle="tooltip" data-html="true" data-placement="left" title="Technician:<br>Location Based Permissions<br>Request Disposals and/or Transfers">Technician</small>';
                                        break;
                                    case 4:
                                        echo '<small class="rounded p-1 m-1 mb-2 bg-lilac text-white d-inline-block pointer" data-toggle="tooltip" data-html="true" data-placement="left" title="User Manager:<br>No User Permissions<br>Location Based Permissions"">User Manager</small>';
                                        break;
                                    case 5:
                                        echo '<small class="rounded p-1 m-1 mb-2 bg-yellow text-white d-inline-block pointer" data-toggle="tooltip" data-html="true" data-placement="left" title="User:<br>No User Permissions<br>View Only - Assets">User</small>';
                                        break;
                                }

                                @endphp
                            </td>
                            <td class="d-none d-md-table-cell">
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
                            </td>
                            <td class="text-right">
                                <div class="dropdown no-arrow">
                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                       id="dropdownMenuLink"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div
                                        class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                        aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">User Options:</div>
                                        @can('view', $user)
                                        <a href="{{ route('users.show', $user->id) }}"
                                           class="dropdown-item">View</a>
                                        @endcan
                                        @can('update', $user)
                                            <a href="{{ route('users.edit', $user->id) }}" class="dropdown-item">Edit</a>
                                        @endcan
                                        @can('delete', $user)
                                            <form id="form{{$user->id}}" action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <a class="deleteBtn dropdown-item" href="#"
                                                   data-id="{{$user->id}}">Delete</a>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow mb-3">
        <div class="card-body">
            <h4>Help with Suppliers</h4>
            <p>This area can be minimised and will contain a little help on the page that the user is currently on.</p>
        </div>
    </div>

</section>

@endsection

@section('modals')
<!-- Delete Modal-->
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
                <small class="text-danger">**Warning this is permanent. </small>
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
<script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
    $('.deleteBtn').click(function() {
            $('#user-id').val($(this).data('id'))
            //showModal
            $('#removeUserModal').modal('show')
        });

        $('#confirmBtn').click(function() {
            var form = '#'+'form'+$('#user-id').val();
            $(form).submit();
        });

        $(document).ready( function () {
            $('#usersTable').DataTable({
                "columnDefs": [ {
                    "targets": [3,4,5],
                    "orderable": false,
                } ],
                "order": [[ 1, "asc"]]
            });
        } );
</script>
@endsection
