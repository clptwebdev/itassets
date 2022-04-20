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

        <div class="row row-eq-height no-gutters p-0 p-md-4 container m-auto">
            <div class="col-12">
                <div class="card shadow h-100">
                    <div class="card-body">
                        <x-handlers.alerts/>
                        <ul id="tab-bar" class="nav nav-tabs">

                            <li class="nav-item">
                                <a class="nav-link active" id="location-tab" data-bs-toggle="tab" href="#location"
                                   role="tab" aria-controls="home" aria-selected="true">Custom Export</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="broadband-tab" data-bs-toggle="tab" href="#broadband" role="tab"
                                   aria-controls="home" aria-selected="true">User Roles</a>
                            </li>
                            @can('update' , \App\Models\Setting::class)
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tab" data-bs-toggle="tab" href="#custom" role="tab"
                                       aria-controls="home" aria-selected="true">Business Settings</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tab" data-bs-toggle="tab" href="#custom" role="tab"
                                       aria-controls="home" aria-selected="true">IT Settings</a>
                                </li>
                                @if(auth()->user()->isGlobal())
                                    <li class="nav-item">
                                        <a class="nav-link" id="developer-tab" data-bs-toggle="tab" href="#developer"
                                           role="tab" aria-controls="home" aria-selected="true">Developer Settings</a>
                                    </li>
                                @endif
                            @endcan
                        </ul>

                        <div class="tab-content border-left border-right border-bottom border-gray" id="myTabContent">
                            <div class="tab-content " id="myTabContent">
                                <div class="tab-pane fade show p-2 pt-4 active" id="location" role="tabpanel"
                                     aria-labelledby="location-tab">
                                    <div class="row">
                                        <div class="col-12 col-md-6 p-4 mb-3 ">
                                            <h3 class="text-primary py-2">Select the button below for custom
                                                                          exports.</h3>
                                            <a data-bs-toggle="modal" data-bs-target="#exportModal"
                                               class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm"> Exports
                                                                                                               Items
                                                                                                               Here</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade p-2 pt-4" id="broadband" role="tabpanel"
                                     aria-labelledby="broadband-tab">
                                    <div class="row">
                                        <div class="col-12 ">
                                            <div class='row justify-content-start m-1 py-2'>

                                                <div class="mb-4 row">
                                                    <div class="col-8">
                                                        <h4 class='text-blue'>Assigning a new Role</h4>
                                                        <a data-bs-toggle="modal" data-bs-target="#roleSyncModal"
                                                           class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm"><i
                                                                class="fas fa-circle-notch fa-sm pl-1 pr-1"></i> Assign
                                                                                                                 a Role
                                                                                                                 to a
                                                                                                                 user</a>
                                                    </div>
                                                    <div class="col-4">
                                                        <table>
                                                            <thead>
                                                            <tr>
                                                                <th colspan="2">Table Key</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td><span class="badge bg-success">7</span></td>
                                                                <td><small>Full Read, Write and Delete Access</small>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><span class="badge bg-warning">6</span></td>
                                                                <td><small>Read and Write Access</small></td>
                                                            </tr>
                                                            <tr>
                                                                <td><span class="badge bg-warning">5</span></td>
                                                                <td><small>Read and Delete Access</small></td>
                                                            </tr>
                                                            <tr>
                                                                <td><span class="badge bg-secondary">4</span></td>
                                                                <td><small>Read Access Only</small></td>
                                                            </tr>
                                                            <tr>
                                                                <td><span class="badge bg-danger">0</span></td>
                                                                <td><small>No Access</small></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <table class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th></th>
                                                        @foreach($roles as $role)
                                                            <th><small>{{$role->name}}</small></th>
                                                        @endforeach
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    @foreach($models as $model)
                                                        <tr>
                                                            <td>
                                                                <small>{{ Illuminate\Support\Str::ucfirst($model)}}</small>
                                                            </td>
                                                            @foreach($roles as $role)
                                                                <td class="text-center">
                                                                    @php
                                                                        $permission =\App\Models\Permission::where('role_id', '=', $role->id)->where('model', '=', Illuminate\Support\Str::ucfirst($model))->first();
                                                                        $chmod = 0;
                                                                        $permission->view ? $chmod = $chmod+4: $chmod;
                                                                        $permission->create ? $chmod = $chmod+2: $chmod;
                                                                        $permission->delete ? $chmod = $chmod+1: $chmod;
                                                                    @endphp
                                                                    <span
                                                                        class="badge @if($chmod == 7) {{'bg-success'}} @elseif($chmod == 0) {{ 'bg-danger'}} @elseif($chmod == 4) {{'bg-secondary'}} @else {{'bg-warning'}} @endif">{{$chmod}}</span>
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade p-2 pt-4" id="custom" role="tabpanel"
                                     aria-labelledby="custom-tab">
                                    <div class="row">
                                        <div class="col-12 ">
                                            <h4 class='text-blue'>Business Settings</h4>
                                            {{--  @can('create' , \App\Models\Setting::class)
                                                 <p class='text-muted'>Click the button below to generate the default settings.</p>
                                                 <a href='{{route('setting.boot')}}'
                                                 class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm"><i
                                                         class="fas fa-plus fa-sm pl-1 pr-1"></i>Create Default Settings</a>
                                                 <a data-bs-toggle='modal' data-bs-target='#settingModal'
                                                 class="d-none d-sm-inline-block btn btn-sm btn-yellow shadow-sm"><i
                                                         class="fas fa-plus fa-sm pl-1 pr-1"></i>Create a New Settings</a>
                                             @endcan --}}

                                            <h5 class='text-blue'>Assets</h5>
                                            <x-form.layout action="#" method="POST">
                                                <div class='form-group m-2'>
                                                    <label for="asset_threshold">Asset Threshold</label>
                                                    <input value='' name='asset_threshold' type="text"
                                                           class="form-control">
                                                    <small class="text-muted">** The Threshold an Asset has to reach for
                                                                              it be calculated in the yearly
                                                                              figures</small>
                                                </div>
                                                <div class='form-group m-2'>
                                                    <label for="default_depreciation">Default Depreciation</label>
                                                    <input value='' name='default_depreciation' type="text"
                                                           class="form-control">
                                                    <small class="text-muted">** If no depreciation is set when
                                                                              adding/uploading Assets, the default shall
                                                                              be (In Years)</small>
                                                </div>
                                                <div class='d-flex justify-content-center mb-2'>
                                                    <x-buttons.submit class="justify-content-center">Submit
                                                    </x-buttons.submit>
                                                </div>
                                            </x-form.layout>
                                        </div>
                                    </div>
                                </div>
                                @if(auth()->user()->isGlobal())
                                    <div class="tab-pane fade p-2 pt-4" id="developer" role="tabpanel"
                                         aria-labelledby="developer-tab">
                                        <div class="row">
                                            <div class="col-12 ">
                                                <h4 class='text-blue m-2'>Developer Settings</h4>
                                                <hr class='rule'>
                                                <h5 class='text-blue mx-4'>Creating a new Role</h5>
                                                <p class='text-muted'>Click the button below to create new role to
                                                                      assign to
                                                                      a
                                                                      User.</p>
                                                <a data-bs-toggle="modal" data-bs-target="#roleAddModal"
                                                   class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm"><i
                                                        class="fas fa-plus fa-sm pl-1 pr-1"></i> Create a new Role</a>
                                                <a data-bs-toggle="modal" data-bs-target="#roleDeleteModal"
                                                   class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
                                                        class="fas fa-minus fa-sm pl-1 pr-1"></i> Remove a Role</a>
                                                <a href='{{route('role.boot')}}'
                                                   class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                                                        class="fas fa-plus fa-sm pl-1 pr-1"></i>Create default Roles</a>
                                                <h5 class='text-blue mx-4 my-3'>Custom Settings</h5>
                                                @can('create' , \App\Models\Setting::class)
                                                    <p class='text-muted'>Click the button below to generate the default
                                                                          settings.</p>
                                                    <a href='{{route('setting.boot')}}'
                                                       class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm"><i
                                                            class="fas fa-plus fa-sm pl-1 pr-1"></i>Create Default
                                                                                                    Settings</a>
                                                    <a data-bs-toggle='modal' data-bs-target='#settingModal'
                                                       class="d-none d-sm-inline-block btn btn-sm btn-yellow shadow-sm"><i
                                                            class="fas fa-plus fa-sm pl-1 pr-1"></i>Create a New
                                                                                                    Settings</a>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
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
    <x-modals.setting-create/>

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
