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
        <x-handlers.alerts/>
        <ul id="tab-bar" class="nav nav-tabs">

            <li class="nav-item">
                <a class="nav-link active" id="location-tab" data-bs-toggle="tab" href="#location" role="tab"
                   aria-controls="home" aria-selected="true">Custom Export</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="broadband-tab" data-bs-toggle="tab" href="#broadband" role="tab"
                   aria-controls="home" aria-selected="true">Role Settings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-tab" data-bs-toggle="tab" href="#custom" role="tab" aria-controls="home"
                   aria-selected="true">Custom Settings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="depreciation-tab" data-bs-toggle="tab" href="#depreciation" role="tab"
                   aria-controls="home" aria-selected="true">Depreciation</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-content " id="myTabContent">
                <div class="tab-pane fade show p-2 pt-4 active" id="location" role="tabpanel"
                     aria-labelledby="location-tab">
                    <div class="row">
                        <div class="col-12 col-md-6 p-4 mb-3 ">
                            <h3 class="text-primary py-2">Select the button below for custom exports.</h3>
                            <a data-bs-toggle="modal" data-bs-target="#exportModal"
                               class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm"> Exports Items Here</a>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade p-2 pt-4" id="broadband" role="tabpanel" aria-labelledby="broadband-tab">
                    <div class="row">
                        <div class="col-12 ">
                            <div class='row justify-content-start m-1 py-2'>
                                <div class='my-4'>
                                    <h4 class='text-blue'>Creating a new Role</h4>
                                    <p class='text-muted'>Click the button below to create new role to assign to a
                                                          User.</p>
                                    <a data-bs-toggle="modal" data-bs-target="#roleAddModal"
                                       class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm"><i
                                            class="fas fa-plus fa-sm pl-1 pr-1"></i> Create a new Role</a>
                                    <a data-bs-toggle="modal" data-bs-target="#roleDeleteModal"
                                       class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
                                            class="fas fa-minus fa-sm pl-1 pr-1"></i> Remove a Role</a>
                                    <a data-bs-toggle="modal" data-bs-target="#roleSyncModal"
                                       class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm"><i
                                            class="fas fa-circle-notch fa-sm pl-1 pr-1"></i> Assign a Role to a user</a>
                                    <a href='{{route('role.boot')}}'
                                       class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                                            class="fas fa-plus fa-sm pl-1 pr-1"></i>Create default Roles</a>
                                </div>
                                <table class="table table-borderless table-responsive">
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
                                                <td class="text-center d-lg-table-cell d-none ">
                                                    @php
                                                        $chmod = 0;
                                                        $permission->view ? $chmod = $chmod+4: $chmod;
                                                        $permission->create ? $chmod = $chmod+2: $chmod;
                                                        $permission->delete ? $chmod = $chmod++: $chmod;
                                                    @endphp
                                                    <span
                                                        class="badge @if($chmod == 7) {{'bg-success'}} @elseif($chmod == 0) {{ 'bg-danger'}} @else {{'bg-warning'}} @endif">{{$chmod}}</span>
                                                </td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td colspan="28"></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade p-2 pt-4" id="custom" role="tabpanel" aria-labelledby="custom-tab">
                    <div class="row">
                        <div class="col-12 ">
                            <h4 class='text-blue'>Custom Settings</h4>
                            <p class='text-muted'>Click the button below to generate the default settings.</p>
                            <a href='{{route('setting.boot')}}'
                               class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm"><i
                                    class="fas fa-plus fa-sm pl-1 pr-1"></i>Create Default Settings</a>
                            <a href='{{route('setting.boot')}}'
                               class="d-none d-sm-inline-block btn btn-sm btn-yellow shadow-sm"><i
                                    class="fas fa-plus fa-sm pl-1 pr-1"></i>Create a New Setting</a>
                            @foreach($settings as $setting)
                                <h5 class='text-center'>{{ ucwords(str_replace(['_' ,'-'] , ' ' ,$setting->name)) ?? 'N/A'}}</h5>
                                <x-form.layout :action="route('settings.update' , $setting->id)" method="PUT">
                                    <div class='d-flex w-100 my-3 justify-content-center'>
                                        <div class='form-group m-2'>
                                            <x-form.input name='name' :value="$setting->name"></x-form.input>
                                        </div>
                                        <div class='form-group m-2'>
                                            <x-form.input name='value' :value="$setting->value"></x-form.input>
                                        </div>
                                        <div class='form-group m-2'>
                                            <x-form.input name="priority" :value="$setting->priority"></x-form.input>
                                        </div>
                                    </div>
                                    <div class='d-flex justify-content-center mb-2'>
                                        <x-buttons.submit class="justify-content-center">Submit</x-buttons.submit>
                                    </div>
                                </x-form.layout>
                                <hr class='w-75 m-auto'>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade p-2 pt-4" id="depreciation" role="tabpanel"
                     aria-labelledby="depreciation-tab">
                    <div class="row">
                        <div class="col-12 ">
                            depreciation
                        </div>
                    </div>
                </div>
            </div>
        </div>


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
