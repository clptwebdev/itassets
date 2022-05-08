<div class="row">
    <div id="searchBar" class="col-4">
        <div class="col-auto">
            <label class="sr-only" for="inlineFormInputGroup">Search</label>
            <form method="POST" action="{{route("manufacturer.filter")}}">
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
    @if(session()->has('manufacturer_search'))
        <div class="col-4">
            <a href="{{ route('manufacturer.clearfilter')}}" class="btn btn-warning shadow-sm">Clear Filter</a>
        </div>
    @endif
</div>
