@php
    $route = strtolower($model);
@endphp
<div id="filter" class="card shadow mb-4">
    <div id="filter-header" class="card-header d-flex justify-content-between align-items-center text-white"
         style="background-color: #474775; border-top-left-radius: 0px;"><h6 class="m-0">Filter Results</h6><a
            class="btn-sm btn-lilac" onclick="javascript:toggleFilter();"><i class="fa fa-times"
                                                                                 aria-hidden="true"></i></a>
    </div>
    <div class="card-body">
        <form action="{{ route($route.'.filter')}}" method="POST">

            <div id="accordion" class="mb-4">
                @csrf
                @if(isset($statuses))
                <div class="option">
                    <div class="option-header pointer collapsed" id="statusHeader" data-toggle="collapse"
                         data-target="#statusCollapse" aria-expanded="true" aria-controls="statusHeader">
                        <small>Status Type</small>
                    </div>

                    <div id="statusCollapse" class="collapse show" aria-labelledby="statusHeader"
                         data-parent="#accordion">
                        <div class="option-body">
                            @foreach($statuses as $status)
                                @if(is_countable($status->${"relations"}))
                                @if($status->${"relations"}->count() != 0 )
                                <div class="form-check">
                                    <label class="form-check-label mr-4"
                                           for="{{'status'.$status->id}}">{{ $status->name }}  ({{$status->${"relations"}->count()}})</label>
                                    <input class="form-check-input" type="checkbox" name="status[]"
                                           value="{{ $status->id}}" id="{{'status'.$status->id}}"
                                           @if(session()->has('status') && in_array($status->id, session('status'))) {{ 'checked'}} @endif>
                                </div>
                                @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                @if(isset($categories))
                <div class="option">
                    <div class="option-header collapsed pointer" id="categoryHeader" data-toggle="collapse"
                         data-target="#categoryCollapse" aria-expanded="true" aria-controls="categoryHeader">
                        <small>Category</small>
                    </div>

                    <div id="categoryCollapse" class="collapse" aria-labelledby="categoryHeader"
                         data-parent="#accordion">
                        <div class="option-body">
                            @foreach($categories as $category)
                                @if($category->${"relations"}->count() != 0)
                                <div class="form-check">
                                    <label class="form-check-label mr-4"
                                           for="{{'category'.$category->id}}">{{ $category->name }} ({{$category->${"relations"}->count()}})</label>
                                    <input class="form-check-input" type="checkbox" name="category[]"
                                           value="{{ $category->id}}" id="{{'category'.$category->id}}"
                                           @if(session()->has('category') && in_array($category->id, session('category'))) {{ 'checked'}} @endif>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                @if(isset($locations))
                <div class="option">
                    <div class="option-header collapsed pointer" id="locationHeader" data-toggle="collapse"
                         data-target="#locationCollapse" aria-expanded="true" aria-controls="locationHeader">
                        <small>Location</small>
                    </div>

                    <div id="locationCollapse" class="collapse" aria-labelledby="locationHeader"
                         data-parent="#accordion">
                        <div class="option-body">
                            @foreach($locations as $location)
                                @if(is_countable($status->${"relations"}))
                                @if($location->${"relations"}->count() != 0)
                            <div class="form-check">
                                <label class="form-check-label mr-4"
                                        for="{{'location'.$location->id}}">{{ $location->name }} ({{$location->${"relations"}->count()}})</label>
                                <input class="form-check-input" type="checkbox" name="locations[]"
                                        value="{{ $location->id}}" id="{{'location'.$location->id}}"
                                        @if(session()->has('locations') && in_array($location->id, session('locations'))) {{ 'checked'}} @endif>
                            </div>
                            @endif
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <div class="option">
                    <div class="option-header collapsed pointer" id="purchasedDateHeader" data-toggle="collapse"
                         data-target="#purchasedDateCollapse" aria-expanded="true"
                         aria-controls="purchasedDateHeader">
                        <small>Purchased Date</small>
                    </div>

                    <div id="purchasedDateCollapse" class="collapse" aria-labelledby="purchasedDateHeader"
                         data-parent="#accordion">
                        <div class="option-body">
                            <div class="form-row">
                                <label for="start" class="p-0 m-0 mb-1"><small>Start</small></label>
                                <input class="form-control" type="date" name="start"
                                        @if(session()->has('start'))
                                        @php $start = \Carbon\Carbon::parse(session('start'))->format('Y-m-d')
                                        @endphp
                                        value="{{ $start }}"
                                        @endif
                                       placeholder="DD/MM/YYYY"/>
                            </div>
                            <div class="form-row">
                                <label for="end" class="p-0 m-0 mb-1"><small>End</small></label>
                                <input class="form-control" type="date" name="end"
                                        @if(session()->has('end'))
                                        @php $end = \Carbon\Carbon::parse(session('end'))->format('Y-m-d')
                                        @endphp
                                        value="{{ $end }}"
                                        @endif
                                        placeholder="DD/MM/YYYY"/>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="option">
                    <div class="option-header collapsed pointer" id="costHeader" data-toggle="collapse"
                         data-target="#costCollapse" aria-expanded="true" aria-controls="costHeader">
                        <small>Purchased Cost</small>
                    </div>

                    <div id="costCollapse" class="collapse" aria-labelledby="costHeader"
                         data-parent="#accordion">
                        <div class="option-body" style="padding-bottom: 60px;">
                            <div class="form-control">
                                <label for="amount">Price range:</label>
                                <input type="text" id="amount" name="amount" readonly
                                       style="border:0; color:#b087bc; font-weight:bold; margin-bottom: 20px;">
                                <div id="slider-range"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="option">
                    <div class="option-header pointer collapsed" id="auditDateHeader" data-toggle="collapse"
                         data-target="#auditDateCollapse" aria-expanded="true" aria-controls="auditDateHeader">
                        <small>Audit Date</small>
                    </div>
                    <div id="auditDateCollapse" class="collapse" aria-labelledby="auditDateHeader"
                         data-parent="#accordion">
                        <div class="option-body">
                            <div class="form-row">
                                <select name="audit" class="form-control">
                                    <option value="0" @if(session()->has('audit') && session('audit') == 0) {{ 'selected'}} @endif>All</option>
                                    <option value="1" @if(session()->has('audit') && session('audit') == 1) {{ 'selected'}} @endif>Overdue Audits</option>
                                    <option value="2" @if(session()->has('audit') && session('audit') == 2) {{ 'selected'}} @endif>In next 30 days</option>
                                    <option value="3" @if(session()->has('audit') && session('audit') == 3) {{ 'selected'}} @endif>In next 3 months</option>
                                    <option value="4" @if(session()->has('audit') && session('audit') == 4) {{ 'selected'}} @endif>In next 6 months</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <button type="submit" class="btn btn-green text-right">Apply Filter</button>
        </form>
    </div>
</div>
