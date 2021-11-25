@props(['style','model'])
<div class="d-flex justify-content-between flex-row-reverse mb-2 p-2">
    @php
        $route = strtolower($model);
    @endphp
    <div id="filterDiv" class="col-3 text-right">
        {{ $filter }}
        @if(isset($filter) && $filter != 0)
            <a href="{{ route($route.'.clear.filter')}}" class="btn btn-warning shadow-sm">Clear Filter</a>
            <div class="dropdown d-inline ml-2">
                <button class="btn btn-green dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
        @if($model == "Component" || $model == "Miscellanea")

        @else
            <a href="#" onclick="javascript:toggleFilter();" class="btn btn-blue shadow-sm ml-2">Filter</a>
        @endif
    </div>
    <div id="searchBar" class="col-4">
        <div class="col-auto">
            <label class="sr-only" for="inlineFormInputGroup">Search</label>
            <form method="POST" action="{{ route($route.'.filter')}}">
                <div class="input-group mb-2">

                    @csrf
                    <input type="text" class="form-control" name="search" placeholder="Search"
                           @if(session()->has('search')) value="{{ session('search') }}" @endif>
                    <div class="input-group-append">
                        <button class="btn btn-blue">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="Sort" class="col-5">
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
                <option value="name asc"
                        @if(session('orderby') == 'name' && (session('direction')) == 'asc') selected @endif>Name (A-Z)
                </option>
                <option value="name desc"
                        @if(session('orderby') == 'name' && (session('direction')) == 'desc') selected @endif>Name (Z-A)
                </option>
                <option value="location_name asc"
                        @if(session('orderby') == 'location_name' && (session('direction')) == 'asc') selected @endif>
                    Location (A-Z)
                </option>
                <option value="location_name desc"
                        @if(session('orderby') == 'location_name' && (session('direction')) == 'desc') selected @endif>
                    Location (Z-A))
                </option>
                <option value="manufacturer_name asc"
                        @if(session('orderby') == 'manufacturer_name' && (session('direction')) == 'asc') selected @endif>
                    Manufacturer (A-Z)
                </option>
                <option value="manufacturer_name desc"
                        @if(session('orderby') == 'manufacturer_name' && (session('direction')) == 'desc') selected @endif>
                    Manufacturer (Z-A)
                </option>
                <option value="purchased_date asc"
                        @if(session('orderby') == 'purchased_date' && (session('direction')) == 'asc') selected
                        @elseif(!session()->has('orderby')) selected @endif>Date (Earliest to Latest)
                </option>
                <option value="purchased_date desc"
                        @if(session('orderby') == 'purchased_date' && (session('direction')) == 'desc') selected
                        @elseif(!session()->has('orderby')) selected @endif>Date (Latest to Earliest)
                </option>
                <option value="purchased_cost asc"
                        @if(session('orderby') == 'purchased_cost' && (session('direction')) == 'asc') selected @endif>
                    Cost (Low to High)
                </option>
                <option value="purchased_cost desc"
                        @if(session('orderby') == 'purchased_cost' && (session('direction')) == 'desc') selected @endif>
                    Cost (High to Low)
                </option>
                <option value="supplier_name asc"
                        @if(session('orderby') == 'supplier_name' && (session('direction')) == 'asc') selected @endif>
                    Supplier (A-Z)
                </option>
                <option value="supplier_name desc"
                        @if(session('orderby') == 'supplier_name' && (session('direction')) == 'desc') selected @endif>
                    Supplier (Z-A)
                </option>
                @if($model === 'Asset')
                    <option value="audit_date asc"
                            @if(session('orderby') == 'audit_date' && (session('direction')) == 'asc') selected @endif>
                        Audit Date (Earliest to Latest)
                    </option>
                    <option value="audit_date desc"
                            @if(session('orderby') == 'audit_date' && (session('direction')) == 'desc') selected @endif>
                        Audit Date (Latest to Earliest)
                    </option>
                @endif
            </select>
            <button class="btn btn-blue" type="submit">Sort</button>
        </form>
    </div>
</div>
