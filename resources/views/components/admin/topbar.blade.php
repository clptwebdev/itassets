<!-- Topbar -->
<nav class="navbar navbar-expand navbar-dark bg-secondary-blue topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Search -->
    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" method="POST"
          action="{{route('assets.search')}}">
        @csrf
        <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for assets..."
                   aria-label="Search" aria-describedby="basic-addon2" name="asset_tag">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Quick Add Features -->

@php
    $logs = App\Models\Log::Latest('created_at')->get()->take(5);

@endphp
<!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                 aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small"
                               placeholder="Search for..." aria-label="Search"
                               aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <li class="nav-item mx-1">
            <a class="nav-link" href="{{route("assets.create")}}">
                <i class="fas fa-fw fa-tablet-alt" data-toggle="tooltip" data-placement="bottom"
                   title="Add New Asset"></i>
                <span class="badge badge-success badge-counter">+</span>
            </a>
        </li>
        <li class="nav-item mx-1">
            <a class="nav-link" href="{{route("documentation.index")}}">
                <i class="fas fa-fw fa-info-circle text-warning" data-toggle="tooltip" data-placement="bottom"
                   title="Documentation"></i>
            </a>
        </li>
        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                @if($logs->count() >=3)
                <span class="badge badge-danger badge-counter">{{$logs->count()}}+</span>
                @else{
                <span class="badge badge-danger badge-counter">{{$logs->count()}}</span>
                @endif
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header" style="background-color:#474775; ">
                    Alerts Center
                </h6>
                @foreach($logs as $log)
                <a class="dropdown-item d-flex align-items-center" href="{{route("logs.index")}}">
                    <div class="mr-3">
                        @if($log->loggable_type == "auth")
                        <div class="icon-circle bg-success">
                            <i class="fas fa-lock text-white"></i>
                        </div>
                        @elseif($log->loggable_type == "user")
                        <div class="icon-circle bg-warning">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        @elseif($log->loggable_type == "location")
                        <div class="icon-circle bg-info">
                            <i class="fas fa-school text-white"></i>
                        </div>
                        @elseif($log->loggable_type == "supplier")
                        <div class="icon-circle bg-info">
                            <i class="fas fa-warehouse text-white"></i>
                        </div>
                        @elseif($log->loggable_type == "component")
                        <div class="icon-circle bg-info">
                            <i class="fas fa-mouse text-white"></i>
                        </div>
                        @elseif($log->loggable_type == "consumable")
                        <div class="icon-circle bg-info">
                            <i class="fas fa-tint text-white"></i>
                        </div>
                        @else
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                        @endif
                    </div>
                    <div>
                        @php
                            //This if statement checks if it's older that the sub hours of the current UK time and sets the text to plain not bold
                            $logTime = $log->updated_at->timestamp;
                            //change number depending on how long you want it to be until new notifications go old
                            $newNotificationTime = Carbon\Carbon::now()->subHours(6)->timestamp;
                        @endphp
                        @if($logTime >= $newNotificationTime)
                        <span class="font-weight-bold">{{$log->data}}</span>
                        @else
                        <span class="">{{$log->data}}</span>
                        @endif
                        <div class="small text-gray-500">{{$log->updated_at->diffForHumans()}}</div>
                    </div>
                </a>
                @endforeach
                @can("viewAll",auth()->user())
                    <a class="dropdown-item text-center small text-gray-500" href="{{route("logs.index")}}">Show All Alerts</a>
                @endcan
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
                <div class="text-right">
                    <span
                        class="mr-2 d-none d-lg-block text-light small">{{ auth()->user()->name ?? 'Nobody Knows'}}</span>
                    <span
                        class="mr-2 d-none d-lg-block text-gray-600 small">{{ auth()->user()->email ?? 'Nobody Knows'}}</span>
                </div>
                @if(auth()->user()->photo()->exists())
                    <img class="img-profile rounded-circle"
                         src="{{ asset(auth()->user()->photo->path) ?? asset('images/profile.png') }}">
                @else
                    <img class="img-profile rounded-circle" src="{{ asset('images/profile.png') }}">
                @endif
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                 aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{route('user.details')}}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="{{route("users.edit",auth()->user()->id)}}">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Edit
                </a>
                <a class="dropdown-item" href="{{route("user.details.activity")}}">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>
<!-- End of Topbar -->
