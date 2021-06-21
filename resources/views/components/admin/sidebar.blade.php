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
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <a class="collapse-item" href="{{ route('assets.index')}}"><i class="far fa-circle text-secondary"></i> All Assets ({{($assetAmount) ?? null}})</a>
                <a class="collapse-item" href="buttons.html"><i class="far fa-circle text-success"></i> Deployed (649)</a>
                <a class="collapse-item" href="buttons.html"><i class="far fa-circle text-danger"></i> Undeployable (143)</a>
                <a class="collapse-item" href="buttons.html"><i class="fas fa-check text-success"></i> Requestable (211)</a>
                <a class="collapse-item" href="buttons.html"><i class="fas fa-check text-warning"></i> Audit Due (211)</a>
                <a class="collapse-item" href="buttons.html"><i class="fas fa-check text-danger"></i> Audit Overdue (211)</a>
                <hr>
                <a href="/asset/create" title="Add New Asset" class="collapse-item">Add New Asset</a>
                <a href="/asset/create" title="Add New Asset" class="collapse-item">Import Assets</a>

        </div>
    </li>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="far fa-fw fa-hdd sidebar-icon"></i>
            <span class="sidebar-title">Components</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Utilities:</h6>
                <a class="collapse-item" href="utilities-color.html">Colors</a>
                <a class="collapse-item" href="utilities-border.html">Borders</a>
                <a class="collapse-item" href="utilities-animation.html">Animations</a>
                <a class="collapse-item" href="utilities-other.html">Other</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true"
            aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-keyboard sidebar-icon"></i>
            <span class="sidebar-title">Accessories</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Utilities:</h6>
                <a class="collapse-item" href="utilities-color.html">Colors</a>
                <a class="collapse-item" href="utilities-border.html">Borders</a>
                <a class="collapse-item" href="utilities-animation.html">Animations</a>
                <a class="collapse-item" href="utilities-other.html">Other</a>
            </div>
        </div>
    </li>
    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true"
            aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-tint sidebar-icon"></i>
            <span class="sidebar-title">Consumables</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Utilities:</h6>
                <a class="collapse-item" href="utilities-color.html">Colors</a>
                <a class="collapse-item" href="utilities-border.html">Borders</a>
                <a class="collapse-item" href="utilities-animation.html">Animations</a>
                <a class="collapse-item" href="utilities-other.html">Other</a>
            </div>
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
                <a class="collapse-item" href="login.html">Depreciation</a>
                <a class="collapse-item" href="{{ route('category.index')}}">Categories</a>
                <a class="collapse-item" href="{{ route('fieldsets.index')}}">Fieldsets</a>
                <a class="collapse-item" href="{{ route('fields.index')}}">Custom Fields</a>
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
