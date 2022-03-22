@extends('layouts.app')

@section('title', 'Settings')

@section('css')
@endsection

@section('content')


    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Settings</h1>
        <div>
            {{--            nav--}}
        </div>
    </div>
    <section>
        <p class="mb-4">Below are the different Settings for the management system. Each has
                        different options.</p>
        @if(session('danger_message'))
            <div class="alert alert-danger"> {!!session('danger_message')!!} </div>
        @endif

        @if(session('success_message'))
            <div class="alert alert-success"> {!! session('success_message')!!} </div>
        @endif
        <h3 class="text-primary py-2">Select the button below for custom exports.</h3>
        <a data-toggle="modal" data-target="#exportModal"
           class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm"> Exports Items Here</a>
        <div class='row justify-content-start m-1 py-2'>
            <div class='my-4'>
                <h4 class='text-blue'>Creating a new Role</h4>
                <p class='text-muted'>Click the button below to create new role to assign to a User.</p>
                <a data-toggle="modal" data-target="#roleAddModal"
                   class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm"><i
                        class="fas fa-plus fa-sm pl-1 pr-1"></i> Create a new Role</a>
                <a data-toggle="modal" data-target="#roleDeleteModal"
                   class="d-none d-sm-inline-block btn btn-sm btn-red shadow-sm"><i
                        class="fas fa-minus fa-sm pl-1 pr-1"></i> Remove a Role</a>
                <a data-toggle="modal" data-target="#roleSyncModal"
                   class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm"><i
                        class="fas fa-circle-notch fa-sm pl-1 pr-1"></i> Assign a Role to a user</a>
                <a href='{{route('role.boot')}}' class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                        class="fas fa-plus fa-sm pl-1 pr-1"></i>Create default Roles</a>
            </div>
        </div>
        <table class="table table-setup">
            <thead>
            <tr>
                <td>Name</td>
                @foreach($models as $model)
                    <th class='d-lg-table-cell d-none '>{{ Illuminate\Support\Str::ucfirst($model)}}</th>
                @endforeach
            </tr>
            </thead>

            <tbody>
            @foreach($roles as $role)
                <tr>
                    <td>{{$role->name}}</td>
                    @foreach($role->permissions as $permission)
                        <td class="text-center d-lg-table-cell d-none">
                            @php
                                $chmod = 0;
                                $permission->view ? $chmod = $chmod+4: $chmod;
                                $permission->create ? $chmod = $chmod+2: $chmod;
                                $permission->delete ? $chmod = $chmod++: $chmod;
                            @endphp
                            <span
                                class="badge @if($chmod == 7) {{'bg-green'}} @elseif($chmod == 0) {{ 'bg-red'}} @else {{'bg-yellow'}} @endif">{{$chmod}}</span>
                        </td>
                    @endforeach
                </tr>
                <tr>
                    <td colspan="28"></td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </section>
@endsection
@section('modals')
    {{--    create javascript auto selct button for roles--}}
    <x-modals.role-add :models='$models'/>
    <x-modals.assign-role/>
    <x-modals.delete-role/>
    <x-components.export :users="$users" :assets="$assets" :components="$components" :accessories="$accessories"
                         :miscellaneous="$miscellaneous" :locations="$locations" :categories="$categories"
                         :statuses="$statuses" :assetModel="$assetModel"/>

@endsection
@section('js')
    <script src="{{ asset('js/roleToggle.js') }}"></script>
    <script>
        const exportModal = new bootstrap.Modal(document.getElementById('exportModal'));
        document.querySelector('#export').addEventListener('click', function (event) {
            event.preventDefault();
            exportModal.show();
        });
    </script>
@endsection
