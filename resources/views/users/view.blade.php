@extends('layouts.app')

@section('css')
<link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet" />
@endsection

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Users</h1>
    <div class="mt-4 mt-sm-0">
        <a href="{{ route('users.create')}}" class="d-inline-block btn btn-sm btn-success shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Add New User</a>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
        @if($users->count() >1)
        <a href="/exportusers" class="d-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i>Export</a>
            @endif
    </div>
</div>

@if(session('danger_message'))
<div class="alert alert-danger"> {{ session('danger_message')}} </div>
@endif

@if(session('success_message'))
<div class="alert alert-success"> {{ session('success_message')}} </div>
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
                                        echo '<small class="rounded p-1 m-1 mb-2 bg-danger text-white d-inline-block pointer" data-toggle="tooltip" data-html="true" data-placement="left" title="No Access Permitted">No Access</small>';
                                        break;
                                    case 1:
                                        echo '<small class="rounded p-1 m-1 mb-2 bg-primary text-white d-inline-block pointer" data-toggle="tooltip" data-html="true" data-placement="left" title="Full Control:<br>Full User Permissions<br>Full Location Permissions">Super Admin</small>';
                                        break;
                                    case 2:
                                        echo '<small class="rounded p-1 m-1 mb-2 bg-info text-white d-inline-block pointer" data-toggle="tooltip" data-html="true" data-placement="left" title="Administrator:<br>Location Based User Permissions<br>Set Location Permissions">Administrator</small>';
                                        break;
                                    case 3:
                                        echo '<small class="rounded p-1 m-1 mb-2 bg-success text-white d-inline-block pointer" data-toggle="tooltip" data-html="true" data-placement="left" title="User Manager:<br>No User Permissions<br>Location Based Permissions">User Manager</small>';
                                        break;
                                    case 4:
                                        echo '<small class="rounded p-1 m-1 mb-2 bg-secondary text-white d-inline-block pointer" data-toggle="tooltip" data-html="true" data-placement="left" title="User:<br>No User Permissions<br>View Only - Assets">User</small>';
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
                                <a href="{{ route('users.show', $user->id) }}"
                                    class="btn-sm btn-secondary text-white d-inline-block d-md-none p-3"><i class="far fa-eye"></i></a>&nbsp;
                                <form id="form{{$user->id}}" action="{{ route('users.destroy', $user->id) }}"
                                    method="POST" class="d-none d-md-inline-block">
                                    <a href="{{ route('users.show', $user->id) }}"
                                        class="btn-sm btn-secondary text-white"><i class="far fa-eye"></i>
                                        View</a>&nbsp;
                                    <a href="{{route('users.edit', $user->id) }}"
                                        class="btn-sm btn-secondary text-white"><i
                                            class="fas fa-pencil-alt"></i></a>&nbsp;

                                    @csrf
                                    @method('DELETE')
                                    @if($user->role_id == 0 || auth()->user()->role_id == 1 || auth()->user()->role_id <= $user->role_id && $user->id != auth()->user()->id)
                                    <a class="btn-sm btn-danger text-white deleteBtn" href="#"
                                        data-id="{{$user->id}}"><i class=" fas fa-trash"></i></a>
                                    @else
                                    <a class="btn-sm btn-secondary text-white" disabled data-toggle="tooltip" data-placement="left" title="Permission Denied"><i class="fas fa-trash"></i></a>
                                    @endif
                                </form>
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
