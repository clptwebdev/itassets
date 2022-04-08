<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <div class="d-flex justify-content-end align-items-center mr-2 mt-2">
        <button id="close_sidebar" class="btn btn-link text-light d-md-none rounded-circle mr-3">
            <i class="fas fa-2x fa-times"></i>
        </button>
    </div>

    <!-- Sidebar - Brand -->
    <a class="d-flex flex-column align-items-center justify-content-center p-2 pt-0 pb-4"
       href="{{ route('dashboard') }}">
        <div id="app-logo">
            <x-application-logo/>
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">
@if(auth()->user()->role_id != 0)
    <!-- Nav Item - Dashboard -->
        <li class="nav-item @if(Request::url() == route('dashboard')) {{ 'active' }} @endif">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt sidebar-icon"></i>
                <span class="sidebar-title">Dashboard</span></a>
        </li>
        {{-- The are the Navigation tabs the Finance Users would be using --}}
        <li class="nav-item @if(Request::url() == route('properties.index') || Str::contains(Request::url(), ['properties', 'property'])) {{ 'active' }} @endif">
            <a class="nav-link text-left text-sm-center text-md-left" href="{{ route('properties.index')}}">
                <i class="fas fa-fw fa-school sidebar-icon"></i>
                <span class="sidebar-title">Property</span></a>
        </li>
        <li class="nav-item @if(Request::url() == route('aucs.index') || Str::contains(Request::url(), ['aucs', 'auc'])) {{ 'active' }} @endif">
            <a class="nav-link text-left text-sm-center text-md-left" href="{{ route('aucs.index')}}">
                <i class="fas fa-fw fa-hammer sidebar-icon"></i>
                <span class="sidebar-title">AUC</span></a>
        </li>
        <li class="nav-item @if(Request::url() == route('ffes.index') || Str::contains(Request::url(), ['ffes', 'ffe'])) {{ 'active' }} @endif">
            <a class="nav-link text-left text-sm-center text-md-left" href="{{ route('ffes.index')}}">
                <i class="fas fa-fw fa-chair sidebar-icon"></i>
                <span class="sidebar-title">FFE</span></a>
        </li>
        <li class="nav-item ">
            <a class="nav-link text-left text-sm-center text-md-left @if(Request::url() == route('machineries.index')) {{ 'active' }} @endif "
               href="{{route('machineries.index')}}">
                <i class="fas fa-fw fa-tractor sidebar-icon"></i>
                <span class="sidebar-title">Plant and Machinery</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-left text-sm-center text-md-left @if(Request::url() == route('vehicles.index')) {{ 'active' }} @endif"
               href="{{route('vehicles.index')}}">
                <i class="fas fa-fw fa-bus sidebar-icon"></i>
                <span class="sidebar-title">Motor Vehicles</span></a>
        </li>
    @endif


<!-- Divider -->

    <hr class="sidebar-divider">
    <li class="nav-item @if(Request::url() == route('softwares.index')) {{ 'active' }} @endif">
        <a class="nav-link text-left text-sm-center text-md-left" href="{{ route('softwares.index')}}">
            <i class="fas fa-fw fa-folder-open sidebar-icon"></i>
            <span class="sidebar-title">Software</span></a>
    </li>
    @if(auth()->user()->role_id != 0)
        @can('viewAll' , \App\Models\Asset::class)
            <li class="nav-item @if(Request::url() == route('assets.index')) {{ 'active' }} @endif">
                <a class="nav-link collapsed text-left text-sm-center text-md-left" href="{{ route('assets.index')}}"
                   data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true"
                   aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-tablet-alt sidebar-icon"></i>
                    <span class="sidebar-title">Computer Equipment
                        <i class="fas fa-fw fa-caret-down sidebar-icon"></i></span>
                </a>
                <div id="collapseTwo"
                     class="collapse p-0 @if(Request::url() == route('assets.index')) {{ 'show' }} @endif"
                     aria-labelledby="headingTwo" data-bs-parent="#accordionSidebar">
                    <a class="sub-link collapse-item" href="{{ route('assets.index')}}"><i
                            class="far fa-circle text-secondary"></i> All Equipment
                                                                      ({{(auth()->user()->location_assets()->count()) ?? null}}
                                                                      )</a>
                    @php
                        $statuses = App\Models\Status::all();
                    @endphp
                    <a href="#statusMenu" class="sub-link collapse-item collapsed d-none d-sm-block"
                       data-bs-toggle="collapse" data-bs-parent="#statusMenu"><i class="fas fa-shield-alt fa-xs"></i> By
                                                                                                                      Status</a>
                    <div class="collapse p-2" id="statusMenu">
                        @foreach($statuses as $status)
                            <a href="{{ route('assets.status', $status->id)}}" title="Add New Asset"
                               class="collapse-item"><i class="{{$status->icon}} fa-xs"
                                                        style="color: {{$status->colour}};"></i> {{ $status->name}}
                            </a>
                        @endforeach
                    </div>
                    <a href="#locationMenu" class="sub-link collapse-item collapsed d-none d-sm-block"
                       data-bs-toggle="collapse" data-bs-parent="#locationMenu"><i class="fas fa-school fa-xs"></i> By
                                                                                                                    Location</a>
                    <div class="collapse p-2" id="locationMenu">
                        @php
                            $locations = auth()->user()->locations;
                        @endphp
                        @foreach($locations as $location)
                            <a href="{{ route('assets.location', $location->id)}}" class="collapse-item"
                               data-bs-parent="#SubSubMenu1"><i class="far fa-circle"
                                                                style="color:{{$location->icon}};"></i> {{ $location->name}}
                            </a>
                        @endforeach
                    </div>
                    @can('recycleBin',\App\Models\Asset::class)
                        <a href="{{ route('assets.bin')}}" title="Recycle Bin" class="collapse-item sub-link"><i
                                class="fas fa-trash-alt fa-xs"></i> Recycle Bin</a>
                    @endcan
                    @can('create',\App\Models\Asset::class)
                        <a href="{{ route('assets.create')}}" title="Add New Asset" class="collapse-item sub-link"><i
                                class="fas fa-plus-circle fa-xs"></i> Add New Equipment</a>
                    @endcan
                    @can('transferAll',\App\Models\Asset::class)
                        <a href="{{ route('transfers.assets')}}" title="Asset Transfers" class="sub-link collapse-item"><i
                                class="fas fa-exchange-alt"></i> Transfers</a>
                    @endcan
                    @can('disposeAll',\App\Models\Asset::class)
                        <a href="{{ route('archives.assets')}}" title="Asset Archives" class="sub-link collapse-item"><i
                                class="fas fa-archive"></i> Disposed/Archived</a>
                    @endcan
                </div>
            </li>
        @endcan
    <!-- Nav Item - Utilities Collapse Menu -->
        @can('viewAll',\App\Models\Accessory::class)
            <li class="nav-item">
                <a class="nav-link collapsed text-left text-sm-center text-md-left"
                   href="{{ route('components.index')}}" data-bs-toggle="collapse" data-bs-target="#accessoryDD"
                   aria-expanded="true" aria-controls="accessoryDD">
                    <i class="fas fa-fw fa-keyboard sidebar-icon"></i>
                    <span
                        class="sidebar-title  @if(Request::url() == route('accessories.index')) {{ 'font-weight-bold' }} @endif">Computer Accessories <i
                            class="fas fa-fw fa-caret-down sidebar-icon"></i></span>
                </a>
                <div id="accessoryDD" class="collapse p-0 text-center text-lg-left" aria-labelledby="accessoryTitle"
                     data-bs-parent="#accordionSidebar">
                    @can('viewAll'  ,\App\Models\Accessory::class)
                        <a class="collapse-item sub-link" href="{{ route('accessories.index')}}"><i
                                class="far fa-circle text-secondary"></i> View All</a>
                    @endcan
                    @can('create'  ,\App\Models\Accessory::class)
                        <a class="collapse-item sub-link" href="{{ route('accessories.create')}}"><i
                                class="fas fa-plus-circle fa-xs"></i> Add New Accessory</a>
                    @endcan
                    @can('recycleBin'  ,\App\Models\Accessory::class)
                        <a href="{{ route('accessories.bin')}}" title="Recycle Bin" class="collapse-item sub-link"><i
                                class="fas fa-trash-alt fa-xs"></i> Recycle Bin</a>
                    @endcan
                    @can('transferAll'  ,\App\Models\Accessory::class)
                        <a href="{{ route('transfers.accessories')}}" title="Accessory Transfers"
                           class="collapse-item sub-link"><i class="fas fa-exchange-alt"></i> Transfers</a>
                    @endcan
                    @can('disposeAll'  ,\App\Models\Accessory::class)
                        <a href="{{ route('archives.accessories')}}" title="Accessory Archives"
                           class="collapse-item sub-link"><i class="fas fa-archive"></i> Disposed/Archived</a>
                    @endcan
                </div>
            </li>
        @endcan
    <!-- Nav Item - Utilities Collapse Menu -->
        @can('viewAll' , \App\Models\Component::class)
            <li class="nav-item">
                <a class="nav-link collapsed text-left text-sm-center text-md-left"
                   href="{{ route('components.index')}}" data-bs-toggle="collapse" data-bs-target="#componentsDD"
                   aria-expanded="true" aria-controls="componentsDD">
                    <i class="far fa-fw fa-hdd sidebar-icon"></i>
                    <span
                        class="sidebar-title @if(Request::url() == route('components.index')) {{ 'font-weight-bold' }} @endif">Computer Components <i
                            class="fas fa-fw fa-caret-down sidebar-icon"></i></span>
                </a>
                <div id="componentsDD" class="collapse" aria-labelledby="componentsTitle"
                     data-bs-parent="#accordionSidebar">
                    @can('viewAll',\App\Models\Component::class)


                        <a class="collapse-item" href="{{ route('components.index')}}">View All</a>
                    @endcan
                    @can('create',\App\Models\Component::class)

                        <a class="collapse-item" href="{{route('components.create')}}"> Add New Component</a>
                    @endcan
                    @can('import',\App\Models\Component::class)
                        <a class="collapse-item" href="{{ route('components.index')}}"> Import Components</a>
                    @endcan
                </div>
            </li>
        @endcan

        {{-- <li class="nav-item">
            <a class="nav-link collapsed text-left text-sm-center text-md-left" href="{{ route('consumables.index')}}" data-bs-toggle="collapse" data-bs-target="#consumableDD" aria-expanded="true"
                aria-controls="consumableDD">
                <i class="fas fa-fw fa-tint sidebar-icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Consumables"></i>
                <span class="sidebar-title">Consumables</span>
            </a>
            <div id="consumableDD" class="collapse" aria-labelledby="consumableTitle" data-bs-parent="#accordionSidebar">
                    <a class="collapse-item" href="{{ route('consumables.index')}}">View All</a>
                    <a class="collapse-item" href="{{ route('consumables.create')}}"> Add New Consumable</a>
                    <a class="collapse-item" href="{{ route('consumables.index')}}"> Import Consumables</a>
            </div>
        </li> --}}
        @can('viewAny' , \App\Models\Miscellanea::class)
            <li class="nav-item">
                <a class="nav-link collapsed text-left text-sm-center text-md-left"
                   href="{{ route('miscellaneous.index')}}" data-bs-toggle="collapse" data-bs-target="#miscellaneousDD"
                   aria-expanded="true" aria-controls="miscellaneousDD">
                    <i class="fas fa-fw fa-question sidebar-icon"></i>
                    <span
                        class="sidebar-title @if(Request::url() == route('miscellaneous.index')) {{ 'font-weight-bold' }} @endif">Miscellaneous <i
                            class="fas fa-fw fa-caret-down sidebar-icon"></i></span>
                </a>
                <div id="miscellaneousDD" class="collapse" aria-labelledby="consumableTitle"
                     data-bs-parent="#accordionSidebar">
                    @can('viewAny' , \App\Models\Miscellanea::class)

                        <a class="collapse-item" href="{{ route('miscellaneous.index')}}">View All</a>
                    @endcan
                    @can('create' , \App\Models\Miscellanea::class)
                        <a class="collapse-item" href="{{ route('miscellaneous.create')}}"> Add New Miscellaneous</a>
                    @endcan
                    @can('import' , \App\Models\Miscellanea::class)

                        <a class="collapse-item" href="{{ route('miscellaneous.index')}}"> Import Miscellaneous</a>
                    @endcan
                </div>
            </li>
        @endcan
    <!-- Divider -->
        <hr class="sidebar-divider">
    <!-- Nav Item - Charts -->
        <li class="nav-item">
            <a class="nav-link text-left text-sm-center text-md-left  @if(Request::url() == route('licenses.index')) {{ 'show' }} @endif "
               href="{{route('licenses.index')}}">
                <i class="far fa-fw fa-id-badge sidebar-icon"></i>
                <span class="sidebar-title">Licenses</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-left text-sm-center text-md-left @if(Request::url() == route('broadbands.index')) {{ 'show' }} @endif"
               href="{{route('broadbands.index')}}">
                <i class="fas fa-fw fa-wifi sidebar-icon"></i>
                <span class="sidebar-title">Broadband</span></a>
        </li>
        @can('viewAll',\App\Models\Order::class )
            <hr class="sidebar-divider">
            <li class="nav-item">
                <a class="nav-link text-left text-sm-center text-md-left @if(Request::url() == route('orders.index')) {{ 'show' }} @endif"
                   href="{{route('orders.index')}}">
                    <i class="fas fa-fw fa-money-check sidebar-icon"></i>
                    <span class="sidebar-title">Orders</span></a>
            </li>
        @endcan
        @can('viewAll',\App\Models\User::class )
            <hr class="sidebar-divider">
    <!-- Nav Item - Pages Collapse Menu -->



            <li class="nav-item">
                <a class="nav-link collapsed text-left text-sm-center text-md-left" href="#" data-bs-toggle="collapse"
                   data-bs-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-users sidebar-icon"></i>
                    <span
                        class="sidebar-title @if(Request::url() == route('users.index')) {{ 'font-weight-bold' }} @endif">Users <i
                            class="fas fa-fw fa-caret-down sidebar-icon"></i></span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages"
                     data-bs-parent="#accordionSidebar">
                    @can('viewAll',\App\Models\User::class)
                        <a class="collapse-item" href="{{ route('users.index')}}">View Users</a>
                    @endcan
                    @can('permissions', \App\Models\User::class)
                        <a class="collapse-item" href="{{ route('user.permissions')}}">Permissions</a>
                    @endcan
                </div>
            </li>
        @endcan
        @can('viewAll' , \App\Models\Location::class)
            <li class="nav-item">
                <a class="nav-link text-left text-sm-center text-md-left" href="{{ route('location.index')}}">
                    <i class="far fa-fw fa-map sidebar-icon"></i>
                    <span
                        class="sidebar-title @if(Request::url() == route('location.index')) {{ 'font-weight-bold' }} @endif">Locations</span></a>
            </li>
        @endcan
    <!-- Nav Item - Charts -->
        @can('viewAny' , \App\Models\Manufacturer::class)
            <li class="nav-item">
                <a class="nav-link text-left text-sm-center text-md-left" href="{{route("manufacturers.index")}}">
                    <i class="fas fa-fw fa-tools sidebar-icon"></i>
                    <span
                        class="sidebar-title @if(Request::url() == route('manufacturers.index')) {{ 'font-weight-bold' }} @endif">Manufacturers</span></a>
            </li>
        @endcan
        @can('viewAny' , \App\Models\Supplier::class)
        <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link text-left text-sm-center text-md-left" href="{{ route('suppliers.index') }}">
                    <i class="fas fa-fw fa-tags sidebar-icon"></i>
                    <span
                        class="sidebar-title @if(Request::url() == route('suppliers.index')) {{ 'font-weight-bold' }} @endif">Suppliers</span></a>
            </li>
        @endcan
    <!-- Divider -->
        <hr class="sidebar-divider my-0">
        <li class="nav-item">
            <a href="{{ route('archives.index')}}" title="Archived"
               class="nav-link text-left text-sm-center text-md-left">
                <i class="fas fa-fw fa-archive sidebar-icon"></i> <span
                    @if(Request::url() == route('archives.index')) {{ 'font-weight-bold' }} @endif class="sidebar-title">Disposed/Archived</span></a>
        </li>
    <!-- Divider -->
        <hr class="sidebar-divider">
    <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed text-left text-sm-center text-md-left" href="#" data-bs-toggle="collapse"
               data-bs-target="#settingPages" aria-expanded="true" aria-controls="settingPages">
                <i class="fas fa-fw fa-cogs sidebar-icon"></i>
                <span class="sidebar-title">Settings <i class="fas fa-fw fa-caret-down sidebar-icon"></i></span>
            </a>
            <div id="settingPages" class="collapse" aria-labelledby="headingPages" data-bs-parent="#accordionSidebar">
                @can('viewAny' , \App\Models\AssetModel::class)
                    <a class="collapse-item @if(Request::url() == route('asset-models.index')) {{ 'font-weight-bold text-white' }} @endif"
                       href="{{ route('asset-models.index')}}">Asset Models</a>
                @endcan
                @can('viewAny' , \App\Models\Depreciation::class)
                    <a class="collapse-item @if(Request::url() == route('depreciation.index')) {{ 'font-weight-bold text-white' }} @endif"
                       href="{{ route('depreciation.index')}}">Depreciation</a>
                @endcan
                @can('viewAny' , \App\Models\Category::class)
                    <a class="collapse-item @if(Request::url() == route('category.index')) {{ 'font-weight-bold text-white' }} @endif"
                       href="{{ route('category.index')}}">Categories</a>
                @endcan
                @can('viewAny' , \App\Models\Fieldset::class)
                    <a class="collapse-item @if(Request::url() == route('fieldsets.index')) {{ 'font-weight-bold text-white' }} @endif"
                       href="{{ route('fieldsets.index')}}">Fieldsets</a>
                @endcan
                @can('viewAny' , \App\Models\Field::class)
                    <a class="collapse-item @if(Request::url() == route('fields.index')) {{ 'font-weight-bold text-white' }} @endif"
                       href="{{ route('fields.index')}}">Custom Fields</a>
                @endcan
                @can('viewAny' , \App\Models\Status::class)
                    <a class="collapse-item @if(Request::url() == route('status.index')) {{ 'font-weight-bold text-white' }} @endif"
                       href="{{ route('status.index')}}">Status Fields</a>
                @endcan
                @can('viewAll' , \App\Models\Setting::class)
                    <a id="export"
                       class="collapse-item  @if(Request::url() == route('settings.view')) {{ 'font-weight-bold text-white' }} @endif"
                       href="{{route("settings.view")}}"> Settings page </a>
                @endcan
                @can('view' , \App\Models\Backup::class)
                    <a class="collapse-item" href="/databasebackups">Database Backups</a>
                @endcan
                <a href="{{ route('reports.index')}}" class="collapse-item">Generated Reports</a>
                <a class="collapse-item" href="{{ route("documentation.index") }}">Documentation</a>
            </div>
        </li>


    <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    @endif
</ul>

<!-- End of Sidebar -->
