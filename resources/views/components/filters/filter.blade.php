@php
    $route = strtolower($model);
@endphp
<div id="filter" class="card shadow mb-4">
    <div id="filter-header" class="card-header d-flex justify-content-between align-items-center text-white"
         style="background-color: #474775; border-top-left-radius: 0px;"><h6 class="m-0">Filter Results</h6><a
            class="btn-sm btn-lilac" onclick="javascript:toggleFilter();"><i class="fa fa-times" aria-hidden="true"></i></a>
    </div>
    <div class="card-body">
        <form action="{{ route($route.'.filter')}}" method="POST">
            <?php $count = $relations . "_count";?>
            <div id="accordion" class="mb-4">
                @csrf
                @if(isset($statuses) && $statuses != null)
                    <div class="option">
                        <div class="option-header pointer collapsed" id="statusHeader" data-bs-toggle="collapse"
                             data-bs-target="#statusCollapse" aria-expanded="true" aria-controls="statusHeader">
                            <small>Status Type</small>
                        </div>

                        <div id="statusCollapse" class="collapse show" aria-labelledby="statusHeader"
                             data-bs-parent="#accordion">
                            <div class="option-body">

                                @foreach($statuses as $status)
                                    @if($status->$count != 0 )
                                        <div class="form-check">
                                            <label class="form-check-label mr-4"
                                                   for="{{'status'.$status->id}}">{{ $status->name }}
                                                ({{$status->$count}})</label>
                                            <input class="form-check-input" type="checkbox" name="status[]"
                                                   value="{{ $status->id}}" id="{{'status'.$status->id}}"
                                            @if(session()->has($relations.'_status') && in_array($status->id, session($relations.'_status'))) {{ 'checked'}} @endif>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($categories) && $categories != null)
                    <div class="option">
                        <div class="option-header collapsed pointer" id="categoryHeader" data-bs-toggle="collapse"
                             data-bs-target="#categoryCollapse" aria-expanded="true" aria-controls="categoryHeader">
                            <small>Category</small>
                        </div>

                        <div id="categoryCollapse" class="collapse" aria-labelledby="categoryHeader"
                             data-bs-parent="#accordion">
                            <div class="option-body">
                                @foreach($categories as $category)
                                    @if($category->$count != 0)
                                        <div class="form-check">
                                            <label class="form-check-label mr-4"
                                                   for="{{'category'.$category->id}}">{{ $category->name }}
                                                ({{$category->${"relations"}->count()}})</label>
                                            <input class="form-check-input" type="checkbox" name="category[]"
                                                   value="{{ $category->id}}" id="{{'category'.$category->id}}"
                                            @if(session()->has($relations.'_category') && in_array($category->id, session($relations.'_category'))) {{ 'checked'}} @endif>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($locations))
                    <div class="option">
                        <div class="option-header collapsed pointer" id="locationHeader" data-bs-toggle="collapse"
                             data-bs-target="#locationCollapse" aria-expanded="true" aria-controls="locationHeader">
                            <small>Location</small>
                        </div>

                        <div id="locationCollapse" class="collapse" aria-labelledby="locationHeader"
                             data-bs-parent="#accordion">
                            <div class="option-body">
                                @foreach($locations as $location)
                                    @if($location->$count != 0)
                                        <div class="form-check">
                                            <label class="form-check-label mr-4"
                                                   for="{{'location'.$location->id}}">{{ $location->name }}
                                                ({{$location->$count}})</label>
                                            <input class="form-check-input" type="checkbox" name="locations[]"
                                                   value="{{ $location->id}}" id="{{'location'.$location->id}}"
                                            @if(session()->has($relations.'_locations') && in_array($location->id, session($relations.'_locations'))) {{ 'checked'}} @endif>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <div class="option">
                    <div class="option-header collapsed pointer" id="purchasedDateHeader" data-bs-toggle="collapse"
                         data-bs-target="#purchasedDateCollapse" aria-expanded="true"
                         aria-controls="purchasedDateHeader">
                        <small>Date</small>
                    </div>

                    <div id="purchasedDateCollapse" class="collapse" aria-labelledby="purchasedDateHeader"
                         data-bs-parent="#accordion">
                        <div class="option-body">
                            <div class="form-row">
                                <label for="start" class="p-0 m-0 mb-1"><small>Start</small></label>
                                <input class="form-control" type="date" name="start"
                                       @if(session()->has('start'))@php $start = \Carbon\Carbon::parse(session($relations.'_start'))->format('Y-m-d')
                                       @endphp value="{{ $start }}" @endif placeholder="DD/MM/YYYY"/>
                            </div>
                            <div class="form-row">
                                <label for="end" class="p-0 m-0 mb-1"><small>End</small></label>
                                <input class="form-control" type="date" name="end"
                                       @if(session()->has('end'))@php $end = \Carbon\Carbon::parse(session($relations.'_end'))->format('Y-m-d')
                                       @endphp value="{{ $end }}" @endif placeholder="DD/MM/YYYY"/>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="option">
                    <div class="option-header collapsed pointer" id="costHeader" data-bs-toggle="collapse"
                         data-bs-target="#costCollapse" aria-expanded="true" aria-controls="costHeader">
                        <small>Cost/Value</small>
                    </div>
                    {{-- new fully javascript slider--}}
                    <div class='m-2'>
                        <label for="customRange1" class="form-label font-weight-bold">MIN </label>
                        <div class='d-flex'>
                            <span>£</span>
                            <p id='minRange'></p>
                        </div>

                        <input type="range" class="form-control-range custom-range" name='minCost' id="customRange1">
                        <label for="customRange2" class="form-label font-weight-bold ">MAX</label>
                        <div class='d-flex'>
                            <span>£</span>
                            <p id='maxRange'></p>
                        </div>
                        <input type="range" class="form-control-range custom-range" name='maxCost' id="customRange2">
                    </div>
                    {{--  end--}}
                </div>

                @if(Schema::hasColumn("{$table}",'audit_date'))
                    <div class="option">
                        <div class="option-header pointer collapsed" id="auditDateHeader" data-bs-toggle="collapse"
                             data-bs-target="#auditDateCollapse" aria-expanded="true" aria-controls="auditDateHeader">
                            <small>Audit Date</small>
                        </div>
                        <div id="auditDateCollapse" class="collapse" aria-labelledby="auditDateHeader"
                             data-bs-parent="#accordion">
                            <div class="option-body">
                                <div class="form-row">
                                    <select name="audit" class="form-control">
                                        <option
                                            value="0" @if(session()->has($relations.'_audit') && session($relations.'_audit') == 0) {{ 'selected'}} @endif>
                                            All
                                        </option>
                                        <option
                                            value="1" @if(session()->has($relations.'_audit') && session($relations.'_audit') == 1) {{ 'selected'}} @endif>
                                            Overdue Audits
                                        </option>
                                        <option
                                            value="2" @if(session()->has($relations.'_audit') && session($relations.'_audit') == 2) {{ 'selected'}} @endif>
                                            In next 30 days
                                        </option>
                                        <option
                                            value="3" @if(session()->has($relations.'_audit') && session($relations.'_audit') == 3) {{ 'selected'}} @endif>
                                            In next 3 months
                                        </option>
                                        <option
                                            value="4" @if(session()->has($relations.'_audit') && session($relations.'_audit') == 4) {{ 'selected'}} @endif>
                                            In next 6 months
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
            <button type="submit" class="btn btn-green text-right">Apply Filter</button>
        </form>
    </div>
</div>
