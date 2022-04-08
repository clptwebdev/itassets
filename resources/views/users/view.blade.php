@extends('layouts.app')

@section('title', 'View all Users')



@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Users</h1>
        <div class="mt-4 mt-sm-0">
            <a href="{{route('user.expired')}}" class="d-inline-block btn btn-sm btn-blue shadow-sm"><i
                    class="fas fa-trash-alt fa-sm text-white-50 pr-1"></i>Check Expired Users</a>
            @can('create' , \App\Models\User::class)
                <x-buttons.add :route="route('users.create')">User(s)</x-buttons.add>
            @endcan
            @can('viewAll', auth()->user())
                @if($users->count() >1)
                    <a href="/exportusers" class="d-inline-block btn btn-sm btn-yellow shadow-sm"><i
                            class="fas fa-download fa-sm text-white-50"></i>Export</a>
                @endif
                <form class="d-inline-block" action="{{ route('users.pdf')}}" method="POST">
                    @csrf
                    <input type="hidden" value="{{ json_encode($users->pluck('id'))}}" name="users"/>
                    <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm loading"><i
                            class="fas fa-file-pdf fa-sm text-dark-50"></i> Generate Report
                    </button>
                </form>

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
                                <td class="text-center d-none d-md-table-cell">{{$user->role->name ?? 'No Role Found'}}
                                </td>
                                <td class="d-none d-md-table-cell">
                                    @php
                                        $locations = $user->locations;
                                    @endphp
                                    @foreach($locations as $location)
                                        <small data-bs-toggle="tooltip" data-html="true" data-bs-placement="left"
                                               title="{{ $location->name }}<br>{{ $location->address1}}"
                                               class="rounded p-1 m-1 mb-2 text-white d-inline-block pointer"
                                               style="background-color: {{$location->icon}}">{{$location->name[0]}}</small>
                                    @endforeach
                                </td>
                                <td class="text-right">
                                    <div class="dropdown no-arrow">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                           id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div
                                            class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">User Options:</div>
                                            @can('view', $user)
                                                <a href="{{ route('users.show', $user->id) }}" class="dropdown-item">View</a>
                                            @endcan
                                            @can('update', $user)
                                                <a href="{{ route('users.edit', $user->id) }}" class="dropdown-item">Edit</a>
                                            @endcan
                                            @can('delete', $user)
                                                <form id="form{{$user->id}}"
                                                      action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="deleteBtn dropdown-item" href="#" data-id="{{$user->id}}">Delete</a>
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
                <h4>Help with Users</h4>
                <p>Click <a href="{{route("documentation.index").'#collapseElevenUsers'}}">here</a> for the
                   Documentation on Users on exporting Adding and Removing!</p>
            </div>
        </div>

    </section>

@endsection

@section('modals')
    <x-modals.delete :archive="true"/>
@endsection

@section('js')
    <script src="{{ asset('js/delete.js') }}"></script>
@endsection
