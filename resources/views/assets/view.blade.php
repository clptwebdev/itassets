@extends('layouts.app')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.theme.min.css" integrity="sha512-9h7XRlUeUwcHUf9bNiWSTO9ovOWFELxTlViP801e5BbwNJ5ir9ua6L20tEroWZdm+HFBAWBLx2qH4l4QHHlRyg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Assets</h1>
        <div>
            @can('create', \App\Models\Asset::class)
            <a href="{{ route('assets.create')}}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                    class="fas fa-plus fa-sm text-white-50"></i> Add New Asset(s)</a>
            @endcan
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
            <a href="/exportassets" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50"></i> Download Csv</a>
        </div>
    </div>

    @if(session('danger_message'))
        <div class="alert alert-danger"> {{ session('danger_message')}} </div>
    @endif

    @if(session('success_message'))
        <div class="alert alert-success"> {{ session('success_message')}} </div>
    @endif

    @php
        $limit = auth()->user()->location_assets()->orderBy('purchased_cost', 'desc')->pluck('purchased_cost')->first();
        $floor = auth()->user()->location_assets()->orderBy('purchased_cost', 'asc')->pluck('purchased_cost')->first();
    @endphp
    <section class="position-relative">
        <p class="mb-4">Below are all the Assets stored in the management system. Each has
            different options and locations can created, updated, deleted and filtered</p>
        <!-- DataTales Example -->
        <div class="d-flex flex-row-reverse mb-2">
            @if(isset($filter))<a href="{{ route('assets.index')}}" class="btn-sm btn-warning p-2 ml-2 shadow-sm">Clear Filter</a>@endif
            <a href="#" onclick="javascript:toggleFilter();" class="btn-sm btn-secondary p-2 shadow-sm">Filter</a>
        </div>
        <div id="filter" class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center text-white" style="background-color: #474775; border-top-left-radius: 0px;"><h6>Filter Results</h6><a class="btn-sm btn-secondary" onclick="javascript:toggleFilter();"><i class="fa fa-times" aria-hidden="true"></i></a></div>
            <div class="card-body">
                <form action="{{ route('assets.filter')}}" method="POST">
                    <div id="accordion" class="mb-4">
                        <div class="option">
                          <div class="option-header pointer collapsed" id="statusHeader" data-toggle="collapse" data-target="#statusCollapse" aria-expanded="true" aria-controls="statusHeader">
                            <small>Status Type</small>
                          </div>
                          @csrf
                          <div id="statusCollapse" class="collapse show" aria-labelledby="statusHeader" data-parent="#accordion">
                            <div class="option-body">
                                @foreach($statuses as $status)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="status[]" value="{{ $status->id}}" id="{{'status'.$status->id}}">
                                        <label class="form-check-label" for="{{'status'.$status->id}}">{{ $status->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                          </div>
                        </div>

                        <div class="option">
                            <div class="option-header collapsed pointer" id="categoryHeader" data-toggle="collapse" data-target="#categoryCollapse" aria-expanded="true" aria-controls="categoryHeader">
                                <small>Category</small>
                            </div>

                            <div id="categoryCollapse" class="collapse" aria-labelledby="categoryHeader" data-parent="#accordion">
                                <div class="option-body">
                                    @foreach($categories as $category)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="category[]" value="{{ $category->id}}" id="{{'category'.$category->id}}">
                                        <label class="form-check-label" for="{{'category'.$category->id}}">{{ $category->name }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                          </div>

                          <div class="option">
                            <div class="option-header collapsed pointer" id="locationHeader" data-toggle="collapse" data-target="#locationCollapse" aria-expanded="true" aria-controls="locationHeader">
                                <small>Location</small>
                            </div>

                            <div id="locationCollapse" class="collapse" aria-labelledby="locationHeader" data-parent="#accordion">
                                <div class="option-body">
                                    @foreach($locations as $location)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="locations[]" value="{{ $location->id}}" id="{{'location'.$location->id}}">
                                        <label class="form-check-label" for="{{'location'.$location->id}}">{{ $location->name }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                          </div>

                          <div class="option">
                            <div class="option-header collapsed pointer" id="purchasedDateHeader" data-toggle="collapse" data-target="#purchasedDateCollapse" aria-expanded="true" aria-controls="purchasedDateHeader">
                                <small>Purchased Date</small>
                            </div>
                            <div id="purchasedDateCollapse" class="collapse" aria-labelledby="purchasedDateHeader" data-parent="#accordion">
                                <div class="option-body">
                                    <div class="form-row">
                                        <label for="start" class="p-0 m-0 mb-1"><small>Start</small></label>
                                        <input class="form-control" type="date" name="start" value="" placeholder="DD/MM/YYYY" />
                                    </div>
                                    <div class="form-row">
                                    <label for="end" class="p-0 m-0 mb-1"><small>End</small></label>
                                        <input class="form-control" type="date" name="end" value="" placeholder="DD/MM/YYYY" />
                                    </div>
                                </div>
                            </div>
                          </div>
                          <div class="option">
                            <div class="option-header collapsed pointer" id="costHeader" data-toggle="collapse" data-target="#costCollapse" aria-expanded="true" aria-controls="costHeader">
                                <small>Purchased Cost</small>
                            </div>
                            <div id="costCollapse" class="collapse" aria-labelledby="costHeader" data-parent="#accordion">
                                <div class="option-body" style="padding-bottom: 60px;">
                                    <div class="form-control">
                                        <label for="amount">Price range:</label>
                                        <input type="text" id="amount" name="amount" readonly style="border:0; color:#b087bc; font-weight:bold; margin-bottom: 20px;">
                                        <div id="slider-range"></div>
                                    </div>
                                </div>
                            </div>
                          </div>

                          <div class="option">
                            <div class="option-header pointer collapsed" id="auditDateHeader" data-toggle="collapse" data-target="#auditDateCollapse" aria-expanded="true" aria-controls="auditDateHeader">
                                <small>Audit Date</small>
                            </div>
                            <div id="auditDateCollapse" class="collapse" aria-labelledby="auditDateHeader" data-parent="#accordion">
                                <div class="option-body">
                                    <div class="form-row">
                                        <select name="audit" class="form-control">
                                            <option value="0">All</option>
                                            <option value="1">Overdue Audits</option>
                                            <option value="2">In next 30 days</option>
                                            <option value="3">In next 3 months</option>
                                            <option value="4">In next 6 months</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                          </div>
                    </div>

                    <button type="submit" class="btn-sm btn-success text-right">Apply Filter</button>
                </form>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="assetsTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="text-center"><input type="checkbox"></th>
                            <th><small>Item</small></th>
                            <th><small>Location</small></th>
                            <th><small>Tag</small></th>
                            <th><small>Manufacturer</small></th>
                            <th><small>Date</small></th>
                            <th><small>Cost</small></th>
                            <th><small>Supplier</small></th>
                            <th class="col-auto"><small>Warranty (M)</small></th>
                            <th class="col-auto text-center"><small>Audit Due</small></th>
                            <th class="text-right col-auto"><small>Options</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="text-center"><input type="checkbox"></th>
                            <th><small>Item</small></th>
                            <th><small>Location</small></th>
                            <th><small>Tag</small></th>
                            <th><small>Manufacturer</small></th>
                            <th><small>Date</small></th>
                            <th><small>Cost</small></th>
                            <th><small>Supplier</small></th>
                            <th><small>Warranty (M)</small></th>
                            <th class="text-center"><small>Audit Due</small></th>
                            <th class="text-right"><small>Options</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($assets as $asset)
                            <tr>
                                <td class="text-center"><input type="checkbox"></td>
                                <td>{{ $asset->model->name ?? 'No Model'}}<br><small>{{ $asset->serial_no }}</small></td>
                                <td class="text-center" data-sort="{{ $asset->location->name ?? 'Unnassigned'}}">
                                    @if(isset($asset->location->photo->path))
                                        '<img src="{{ asset($asset->location->photo->path)}}" height="30px" alt="{{$asset->location->name}}" title="{{ $asset->location->name ?? 'Unnassigned'}}"/>'
                                    @else
                                        {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($asset->location->icon ?? '#666').'">'
                                            .strtoupper(substr($asset->location->name ?? 'u', 0, 1)).'</span>' !!}
                                    @endif
                                </td>
                                <td>{{ $asset->asset_tag }}</td>
                                <td class="text-center">{{ $asset->model->manufacturer->name ?? 'N/A' }}</td>
                                <td data-sort="{{ strtotime($asset->purchased_date)}}">{{ \Carbon\Carbon::parse($asset->purchased_date)->format('d/m/Y')}}</td>
                                <td class="text-center">
                                    £{{ $asset->purchased_cost }}<br>
                                    @php
                                    $age = Carbon\Carbon::now()->floatDiffInYears($asset->purchased_date);
                                    $percentage = floor($age)*33.333;
                                    $dep = $asset->purchased_cost * ((100 - $percentage) / 100);
                                    @endphp
                                    <small>(*£{{ number_format($dep, 2)}})</small>
                                </td>
                                <td class="text-center">{{$asset->supplier->name ?? "N/A"}}</td>
                                @php $warranty_end = \Carbon\Carbon::parse($asset->purchased_date)->addMonths($asset->warranty);@endphp
                                <td class="text-center" data-sort="{{ $warranty_end }}">
                                    {{ $asset->warranty }} Months

                                    <br><small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</small>
                                </td>
                                <td class="text-center" data-sort="{{ strtotime($asset->audit_date)}}">
                                    @if(\Carbon\Carbon::parse($asset->audit_date)->isPast())
                                        <span class="text-danger">{{\Carbon\Carbon::parse($asset->audit_date)->format('d/m/Y') }}</span><br><small>Audit Overdue</small>
                                    @else
                                        <?php $age = Carbon\Carbon::now()->floatDiffInDays($asset->audit_date);?>
                                        @switch(true)
                                            @case($age < 31) <span class="text-warning">{{ \Carbon\Carbon::parse($asset->audit_date)->format('d/m/Y') }}</span><br><small>Audit Due Soon</small>
                                                @break
                                            @default
                                                <span class="text-secondary">{{ \Carbon\Carbon::parse($asset->audit_date)->format('d/m/Y') }}</span><br><small>Audit due in {{floor($age)}} days</small>
                                        @endswitch
                                    @endif
                                </td>
                                <td class="text-right">
                                    <form id="form{{$asset->id}}" action="{{ route('assets.destroy', $asset->id) }}"
                                          method="POST">
                                        <a href="{{ route('assets.show', $asset->id) }}"
                                           class="btn-sm btn-secondary text-white"><i class="far fa-eye"></i></a>&nbsp;
                                           @can('edit', $asset)
                                        <a href="{{route('assets.edit', $asset->id) }}"
                                           class="btn-sm btn-secondary text-white"><i class="fas fa-pencil-alt"></i></a>&nbsp;
                                            @endcan

                                        @csrf
                                        @method('DELETE')

                                        @can('delete', $asset)
                                        <a class="btn-sm btn-danger text-white deleteBtn" href="#"
                                           data-id="{{$asset->id}}"><i class=" fas fa-trash"></i></a>
                                           @endcan
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow mb-3">
            <div class="card-body">
                <h4>Help with Assets</h4>
                <p>This area can be minimised and will contain a little help on the page that the user is currently
                    on.</p>
            </div>
        </div>

    </section>
@endsection

@section('modals')
    <!-- Delete Modal-->
    <div class="modal fade bd-example-modal-lg" id="removeassetModal" tabindex="-1" role="dialog"
         aria-labelledby="removeassetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeassetModalLabel">Are you sure you want to delete this asset?
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input id="asset-id" type="hidden" value="">
                    <p>Select "Delete" to remove this asset from the system.</p>
                    <small class="text-danger">**Warning this is permanent. All assets assigned to this asset will be
                        set to Null.</small>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" type="button" id="confirmBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function toggleFilter(){
            if($('#filter').hasClass('show')){
                $('#filter').removeClass('show');
                $('#filter').css('right', '-100%');
            }else{
                $('#filter').addClass('show');
                $('#filter').css('right', '0%');
            }
        }


        $('.deleteBtn').click(function() {
        $('#supplier-id').val($(this).data('id'))
        //showModal
        $('#removeSupplierModal').modal('show')
        });

        $('#confirmBtn').click(function() {
        var form = '#'+'form'+$('#supplier-id').val();
        $(form).submit();
        });

        $( function() {
            $( "#slider-range" ).slider({
            range: true,
            min: {{ floor($floor)}},
            max: {{ round($limit)}},
            values: [ {{ floor($floor)}}, {{ round($limit)}} ],
            slide: function( event, ui ) {
                $( "#amount" ).val( "£" + ui.values[ 0 ] + " - £" + ui.values[ 1 ] );
            }
            });
            $( "#amount" ).val( "£" + $( "#slider-range" ).slider( "values", 0 ) +
            " - £" + $( "#slider-range" ).slider( "values", 1 ) );
        } );

        $(document).ready( function () {
            $('#assetsTable').DataTable({
                "columnDefs": [ {
                "targets": [0, 10],
                "orderable": false,
                } ],
                "order": [[ 1, "asc"]]
            });
        });
    </script>
@endsection
