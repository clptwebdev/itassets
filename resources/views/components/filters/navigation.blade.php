<div class="d-flex justify-content-between flex-row-reverse mb-2 p-2">
    @php
    $route = strtolower($model);
    @endphp
    <div id="filterDiv" class="col-3 text-right">
        @if(isset($filter) && $filter != 0)
        <a href="{{ route($route.'.clear.filter')}}" class="btn btn-warning shadow-sm">Clear Filter</a>
        <div class="dropdown d-inline ml-2">
            <button class="btn btn-green dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              View Filter
            </button>
            <div class="dropdown-menu dropdown-menu-right text-center" aria-labelledby="dropdownMenuButton">
               
                @if(session()->has('status'))
                    <h6 class="dropdown-header text-center">Status</h6>
                    @foreach(session('status') as $id => $key)
                        @php
                        $status = \App\Models\Status::find($key);
                        @endphp
                        <span class="dropdown-item">{{ $status->name }} </span>
                        @php
                            unset($status);
                        @endphp
                    @endforeach
                @endif
                @if(session()->has('locations'))
                    <h6 class="dropdown-header text-center">Locations</h6>
                    @foreach(session('locations') as $id => $key)
                        @php
                        $location = \App\Models\Location::find($key);
                        @endphp
                        <span class="dropdown-item">{{ $location->name }} </span>
                        @php
                            unset($location);
                        @endphp
                    @endforeach
                @endif
                @if(session()->has('category'))
                    <h6 class="dropdown-header text-center">Categories</h6>
                    @foreach(session('category') as $id => $key)
                        @php
                        $category = \App\Models\category::find($key);
                        @endphp
                        <span class="dropdown-item">{{ $category->name }} </span>
                        @php
                            unset($category);
                        @endphp
                    @endforeach
                @endif
                @if(session()->has('start') && session()->has('end'))
                    <h6 class="dropdown-header text-center">Purchased Date</h6>
                    <span class="dropdown-item">{{ session('start').' to '.session('end') ?? 'No Dates'}}</span>
                @endif
                @if(session()->has('amount'))
                    <h6 class="dropdown-header text-center">Purchased Cost</h6>
                    <span class="dropdown-item">{{ session('amount')}}</span>
                @endif
                @if(session()->has('audit') && session('audit') != 0)
                    <h6 class="dropdown-header text-center">Audit</h6>
                    @php
                    switch(session('audit')){
                        case 1:
                            echo '<span class="dropdown-item">Overdue Audits</span>';
                            break;
                        case 2:
                            echo '<span class="dropdown-item">In Next 30 Days</span>';
                            break;
                        case 3:
                            echo '<span class="dropdown-item">In Next 3 Months</span>';
                            break;
                        case 4:
                            echo '<span class="dropdown-item">In Next 6 Months</span>';
                            break;
                        Default:
                            break;
                    }
                    @endphp
                @endif
                @if(session()->has('search'))
                    <h6 class="dropdown-header text-center">Search</h6>
                    <span class="dropdown-item">"{{ session('search')}}"</span>
                @endif
            </div>
          </div>
        @endif
        <a href="#" onclick="javascript:toggleFilter();" class="btn btn-blue shadow-sm ml-2">Filter</a>
    </div>
    <div id="searchBar" class="col-5">
        <div class="col-auto">
        <label class="sr-only" for="inlineFormInputGroup">Search</label>
        <form method="POST" action="{{ route($route.'.filter')}}">
        <div class="input-group mb-2">
            
                    @csrf
                <input type="text" class="form-control" name="search" placeholder="Search" @if(session()->has('search')) value="{{ session('search') }}" @endif>
                <div class="input-group-append">
                <button class="btn btn-blue">Search</button>
                
                </div>
            </div>
        </form>
        </div>  
    </div>
    <div id="Sort" class="col-4">
        <form class="form-inline" method="POST" action="{{ route($route.'.filter')}}">
            @csrf
            <label class="my-1 mr-2">Amount:</label>
            <select class="form-control mr-2" name="limit">
                <option value="25" @if(session('limit') == 25) selected @endif>25</option>
                <option value="50" @if(session('limit') == 50) selected @endif>50</option>
                <option value="100" @if(session('limit') == 100) selected @endif>100</option>
            </select>
            <label class="my-1 mr-2">Order By:</label>
            <select class="form-control mr-2" name="orderby">
                <option value="name" @if(session('orderby') == 'name') selected @endif>Name</option>
                <option value="location_name" @if(session('orderby') == 'locations.name') selected @endif>Location</option>
                <option value="asset_tag" @if(session('orderby') == 'asset_tag') selected @endif>Asset tag</option>
                <option value="manufacturer_name" @if(session('orderby') == 'manufacturer_id') selected @endif>Manufacturer</option>
                <option value="purchased_date" @if(session('orderby') == 'purchased_date') selected @elseif(!session()->has('orderby')) selected @endif>Date</option>
                <option value="purchased_cost" @if(session('orderby') == 'purchased_cost') selected @endif>Cost</option>
                <option value="supplier_id" @if(session('orderby') == 'supplier_id') selected @endif>Supplier</option>
                <option value="audit_date" @if(session('orderby') == 'audit_date') selected @endif>Audit Date</option>
            </select>
            <button class="btn btn-blue" type="submit">Sort</button>
        </form>
    </div>
</div>