<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="d-flex flex-column align-items-center justify-content-center p-4" href="{{ route('dashboard') }}">
        <div id="app-logo"><x-application-logo /></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt sidebar-icon"></i>
            <span class="sidebar-title">Dashboard</span></a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider">
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('assets.index')}}" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-tablet-alt sidebar-icon" data-toggle="tooltip" data-placement="right" title="Components"></i>
            <span class="sidebar-title">Assets</span>
        </a>
        <div id="collapseTwo" class="collapse p-0" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <a class="sub-link collapse-item" href="{{ route('assets.index')}}"><i class="far fa-circle text-secondary"></i> All Assets (@if(auth()->user()->role_id == 1){{ \App\Models\Asset::all()->count()}}@else{{(auth()->user()->location_assets()->count()) ?? null}}@endif)</a>
                @php
                $statuses = App\Models\Status::all();
                @endphp
                <a href="#statusMenu" class="sub-link collapse-item collapsed d-none d-sm-block" data-toggle="collapse" data-parent="#statusMenu"><i class="fas fa-shield-alt fa-xs"></i> By Status</a>
                <div class="collapse p-2" id="statusMenu">
                    @foreach($statuses as $status)
                    <a href="{{ route('assets.status', $status->id)}}" title="Add New Asset" class="collapse-item"><i class="{{$status->icon}} fa-xs" style="color: {{$status->colour}};"></i> {{ $status->name}}</a>
                    @endforeach
                </div>
                <a href="#locationMenu" class="sub-link collapse-item collapsed d-none d-sm-block" data-toggle="collapse" data-parent="#locationMenu"><i class="fas fa-school fa-xs"></i> By Location</a>
                <div class="collapse p-2" id="locationMenu">
                    @foreach(auth()->user()->locations as $location)
                    <a href="{{ route('assets.location', $location->id)}}" class="collapse-item" data-parent="#SubSubMenu1"><i class="far fa-circle" style="color:{{$location->icon}};"></i> {{ $location->name}}</a>
                    @endforeach
                </div>
                <a href="{{ route('assets.bin')}}" title="Recycle Bin" class="collapse-item sub-link"><i class="fas fa-trash-alt fa-xs"></i> Recycle Bin</a>
                <a href="{{ route('assets.create')}}" title="Add New Asset" class="collapse-item sub-link"><i class="fas fa-plus-circle fa-xs"></i> Add New Asset</a>
                <a href="{{route("assets.index")}}" title="Import Assets" class="sub-link collapse-item">Import Assets</a>


        </div>
    </li>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('components.index')}}" data-toggle="collapse" data-target="#componentsDD" aria-expanded="true"
            aria-controls="componentsDD">
            <i class="far fa-fw fa-hdd sidebar-icon" data-toggle="tooltip" data-placement="right" title="Components"></i>
            <span class="sidebar-title">Components</span>
        </a>
        <div id="componentsDD" class="collapse" aria-labelledby="componentsTitle" data-parent="#accordionSidebar">
                <a class="collapse-item" href="{{ route('components.index')}}">View All</a>
                <a class="collapse-item" href="{{route('components.create')}}"> Add New Component</a>
                <a class="collapse-item" href="{{ route('components.index')}}"> Import Components</a>
        </div>
    </li>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('components.index')}}" data-toggle="collapse" data-target="#accessoryDD" aria-expanded="true"
            aria-controls="accessoryDD">
            <i class="fas fa-fw fa-keyboard sidebar-icon" data-toggle="tooltip" data-placement="right" title="Accessories"></i>
            <span class="sidebar-title">Accessories</span>
        </a>
        <div id="accessoryDD" class="collapse" aria-labelledby="accessoryTitle" data-parent="#accordionSidebar">
                <a class="collapse-item" href="{{ route('accessories.index')}}">View All</a>
                <a class="collapse-item" href="{{ route('accessories.create')}}"> Add New Accessory</a>
                <a class="collapse-item" href="{{ route('accessories.index')}}"> Import Accessories</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('consumables.index')}}" data-toggle="collapse" data-target="#consumableDD" aria-expanded="true"
            aria-controls="consumableDD">
            <i class="fas fa-fw fa-tint sidebar-icon" data-toggle="tooltip" data-placement="right" title="Consumables"></i>
            <span class="sidebar-title">Consumables</span>
        </a>
        <div id="consumableDD" class="collapse" aria-labelledby="consumableTitle" data-parent="#accordionSidebar">
                <a class="collapse-item" href="{{ route('consumables.index')}}">View All</a>
                <a class="collapse-item" href="{{ route('consumables.create')}}"> Add New Consumable</a>
                <a class="collapse-item" href="{{ route('consumables.index')}}"> Import Consumables</a>
        </div>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
            aria-controls="collapsePages">
            <i class="fas fa-fw fa-users sidebar-icon"></i>
            <span class="sidebar-title">Users</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <a class="collapse-item" href="{{ route('users.index')}}">View Users</a>
            <a class="collapse-item" href="register.html">Register</a>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('location.index')}}">
            <i class="far fa-fw fa-map sidebar-icon"></i>
            <span class="sidebar-title">Locations</span></a>
    </li>
    <!-- Nav Item - Charts -->
    <li class="nav-item">
        <a class="nav-link" href="/manufacturers">
            <i class="fas fa-fw fa-tools sidebar-icon"></i>
            <span class="sidebar-title">Manufacturers</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('supplier.index') }}">
            <i class="fas fa-fw fa-tags sidebar-icon"></i>
            <span class="sidebar-title">Suppliers</span></a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider">
    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#settingPages" aria-expanded="true"
            aria-controls="settingPages">
            <i class="fas fa-fw fa-cogs sidebar-icon"></i>
            <span class="sidebar-title">Settings</span>
        </a>
        <div id="settingPages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <a class="collapse-item" href="{{ route('asset-models.index')}}">Asset Models</a>
                <a class="collapse-item" href="{{ route('depreciation.index')}}">Depreciation</a>
                <a class="collapse-item" href="{{ route('category.index')}}">Categories</a>
                <a class="collapse-item" href="{{ route('fieldsets.index')}}">Fieldsets</a>
                <a class="collapse-item" href="{{ route('fields.index')}}">Custom Fields</a>
                <a class="collapse-item" href="{{ route('status.index')}}">Status Fields</a>
                <a class="collapse-item" href="login.html">Settings</a>
        </div>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
