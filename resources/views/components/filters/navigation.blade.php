<div class="d-flex justify-content-between flex-wrap flex-row-reverse mb-2">
    @php
        $route = strtolower($model);
    @endphp
    <div id="filterDiv" class="col-12 mb-4 text-right">
        <a id="sortButton" href="#" class="btn btn-blue d-lg-none"><i class="fas fa-lg fa-sort"></i></a>
        <a id="searchButton" href="#" class="btn btn-blue d-lg-none"><i class="fas fa-lg fa-search"></i></a>
        @if(session()->has($relations."_filter") && session($relations.'_filter') != false)
            <a href="{{ route($route.'.clear.filter')}}" class="btn btn-warning shadow-sm">Clear Filter</a>
            <div class="dropdown d-inline ml-lg-2">
                <button class="btn btn-green dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="far fa-eye mr-lg-1"></i><span class="d-none d-lg-inline-block">View Filter</span>
                </button>
                <div class="dropdown-menu dropdown-menu-right text-center" aria-labelledby="dropdownMenuButton">

                    {{-- Check to see if the model has the relationship and if the session is set --}}
                    @if(session()->has($relations.'_status'))
                        <h6 class="dropdown-header text-center">Status</h6>
                        @foreach(session($relations.'_status') as $id => $key)
                            @php
                                $status = \App\Models\Status::find($key);
                            @endphp
                            <span class="dropdown-item">{{ $status->name }} </span>
                            @php
                                unset($status);
                            @endphp
                        @endforeach
                    @endif

                    @if(session()->has($relations.'_locations'))
                        <h6 class="dropdown-header text-center">Locations</h6>
                        @foreach(session($relations.'_locations') as $id => $key)
                            @php
                                $location = \App\Models\Location::find($key);
                            @endphp
                            <span class="dropdown-item">{{ $location->name }} </span>
                            @php
                                unset($location);
                            @endphp
                        @endforeach
                    @endif

                    @if(session()->has($relations.'_category'))
                        <h6 class="dropdown-header text-center">Categories</h6>
                        @foreach(session($relations.'_category') as $id => $key)
                            @php
                                $category = \App\Models\category::find($key);
                            @endphp
                            <span class="dropdown-item">{{ $category->name }} </span>
                            @php
                                unset($category);
                            @endphp
                        @endforeach
                    @endif
                    @if(session()->has($relations.'_start') && session()->has($relations.'_end'))
                        <h6 class="dropdown-header text-center">Purchased Date</h6>
                        <span
                            class="dropdown-item">{{ session($relations.'_start').' to '.session($relations.'_end') ?? 'No Dates'}}</span>
                    @endif

                    @if(session()->has($relations.'_amount'))
                        <h6 class="dropdown-header text-center">Purchased Cost/Value</h6>
                        <span class="dropdown-item">{{ session($relations.'_amount')}}</span>
                    @endif


                    @if(session()->has($relations.'_audit') && session($relations.'_audit') != 0)
                        <h6 class="dropdown-header text-center">Audit</h6>
                        @php
                            switch(session($relations.'_audit')){
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
                    @if(session()->has($relations.'_search'))
                        <h6 class="dropdown-header text-center">Search</h6>
                        <span class="dropdown-item">"{{ session($route.'_search')}}"</span>
                    @endif
                </div>
            </div>
        @endif
        <a id="filterBtn" href="#" onclick="javascript:toggleFilter();" class="btn btn-blue shadow-sm ml-lg-2"><i
                class="fas fa-filter mr-lg-1"></i><span class="d-none d-lg-inline-block">Filter</span></a>
    </div>
    <div id="searchBar" class="d-none d-lg-inline-block col-12 col-lg-4 mb-4 mb-lg-0">
        <div class="w-100">
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

    <div id="sortBar" class="d-none d-lg-inline-block col-12 col-lg-8">
        <form class="form-inline w-100" method="POST" action="{{ route($route.'.filter')}}">
            @csrf
            <label class="my-1 mr-2"><i class="fas fa-list-ol"></i></label>
            <select class="form-control mr-2" name="limit">
                <option value="25" @if(session($relations.'_limit') == 25) selected @endif>25</option>
                <option value="50" @if(session($relations.'_limit') == 50) selected @endif>50</option>
                <option value="100" @if(session($relations.'_limit') == 100) selected @endif>100</option>
            </select>
            <label class="my-1 mr-2"><i class="fas fa-sort"></i></label>
            <select class="form-control mr-2" name="orderby">
                {{-- Check to see if the Model has a column name --}}
                @if(Schema::hasColumn("{$table}",'name'))
                    <option value="name asc"
                            @if(session($relations.'_orderby') == 'name' && (session($relations.'_direction')) == 'asc') selected @endif>
                        Name (A-Z)
                    </option>

                    <option value="name desc"
                            @if(session($relations.'_orderby') == 'name' && (session($relations.'_direction')) == 'desc') selected @endif>
                        Name (Z-A)
                    </option>
                @endif
                @if(Schema::hasColumn("{$table}",'location_id'))
                    <option value="location_name asc"
                            @if(session($relations.'_orderby') == 'location_name' && (session($relations.'_direction')) == 'asc') selected @endif>
                        Location (A-Z)
                    </option>
                    <option value="location_name desc"
                            @if(session($relations.'_orderby') == 'location_name' && (session($relations.'_direction')) == 'desc') selected @endif>
                        Location (Z-A))
                    </option>
                @endif
                @if(Schema::hasColumn("{$table}",'manufacturer_id'))
                    <option value="manufacturer_name asc"
                            @if(session($relations.'_orderby') == 'manufacturer_name' && (session($relations.'_direction')) == 'asc') selected @endif>
                        Manufacturer (A-Z)
                    </option>
                    <option value="manufacturer_name desc"
                            @if(session($relations.'_orderby') == 'manufacturer_name' && (session($relations.'_direction')) == 'desc') selected @endif>
                        Manufacturer (Z-A)
                    </option>
                @endif
                @if(Schema::hasColumn("{$table}",'purchased_date'))
                    <option value="purchased_date asc"
                            @if(session($relations.'_orderby') == 'purchased_date' && (session($relations.'_direction')) == 'asc') selected
                            @elseif(!session()->has('orderby')) selected @endif>Date (Earliest to Latest)
                    </option>
                    <option value="purchased_date desc"
                            @if(session($relations.'_orderby') == 'purchased_date' && (session($relations.'_direction')) == 'desc') selected
                            @elseif(!session()->has('orderby')) selected @endif>Date (Latest to Earliest)
                    </option>
                @endif
                @if(Schema::hasColumn("{$table}",'purchased_cost'))
                    <option value="purchased_cost asc"
                            @if(session($relations.'_orderby') == 'purchased_cost' && (session($relations.'_direction')) == 'asc') selected @endif>
                        Cost (Low to High)
                    </option>
                    <option value="purchased_cost desc"
                            @if(session($relations.'_orderby') == 'purchased_cost' && (session($relations.'_direction')) == 'desc') selected @endif>
                        Cost (High to Low)
                    </option>
                @endif
                @if(Schema::hasColumn("{$table}",'supplier_id'))
                    <option value="supplier_name asc"
                            @if(session($relations.'_orderby') == 'supplier_name' && (session($relations.'_direction')) == 'asc') selected @endif>
                        Supplier (A-Z)
                    </option>
                    <option value="supplier_name desc"
                            @if(session($relations.'_orderby') == 'supplier_name' && (session($relations.'_direction')) == 'desc') selected @endif>
                        Supplier (Z-A)
                    </option>
                @endif
                @if(Schema::hasColumn("{$table}",'audit_date'))
                    <option value="audit_date asc"
                            @if(session($relations.'_orderby') == 'audit_date' && (session($relations.'_direction')) == 'asc') selected @endif>
                        Audit Date (Earliest to Latest)
                    </option>
                    <option value="audit_date desc"
                            @if(session($relations.'_orderby') == 'audit_date' && (session($relations.'_direction')) == 'desc') selected @endif>
                        Audit Date (Latest to Earliest)
                    </option>
                @endif
            </select>
            <button class="btn btn-blue" type="submit">Sort</button>
        </form>
    </div>
</div>
