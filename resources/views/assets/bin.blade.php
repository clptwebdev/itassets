@extends('layouts.app')

@section('title', 'Recycle Bin | Assets')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.theme.min.css" integrity="sha512-9h7XRlUeUwcHUf9bNiWSTO9ovOWFELxTlViP801e5BbwNJ5ir9ua6L20tEroWZdm+HFBAWBLx2qH4l4QHHlRyg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Assets | Recycle Bin</h1>
        <div>
            <a href="{{ route('assets.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                class="fas fa-chevron-left fa-sm text-dark-50"></i> Back to Assets</a>
            @can('generatePDF', \App\Models\Asset::class)
            <form class="d-inline-block" action="{{ route('assets.pdf')}}" method="POST">
                @csrf
                <input type="hidden" value="{{ json_encode($assets->pluck('id'))}}" name="assets"/>
            <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm"><i
                    class="fas fa-file-pdf fa-sm text-white-50"></i> Generate Report</button>
            </form>
            @endcan
        </div>
    </div>

    @if(session('danger_message'))
        <div class="alert alert-danger"> {{ session('danger_message')}} </div>
    @endif

    @if(session('success_message'))
        <div class="alert alert-success"> {{ session('success_message')}} </div>
    @endif

    <section>
        <p class="mb-4">Below are all the Assets stored in the management system. Each has
            different options and locations can created, updated, deleted and filtered</p>
        <!-- DataTales Example -->
        

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="assetsTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-9 col-md-3"><small>Item</small></th>
                            <th class="col-1 col-md-auto"><small>Location</small></th>
                            <th class="col-1 col-md-auto"><small>Tag</small></th>
                            <th class="d-none d-xl-table-cell"><small>Manufacturer</small></th>
                            <th class="d-none d-xl-table-cell"><small>Date</small></th>
                            <th class="d-none d-xl-table-cell"><small>Cost</small></th>
                            <th class="d-none d-xl-table-cell"><small>Supplier</small></th>
                            <th class="text-right col-2"><small>Options</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th><small>Item</small></th>
                            <th><small>Location</small></th>
                            <th><small>Tag</small></th>
                            <th class="d-none d-xl-table-cell"><small>Manufacturer</small></th>
                            <th class=" d-none d-xl-table-cell"><small>Date</small></th>
                            <th class=" d-none d-xl-table-cell"><small>Cost</small></th>
                            <th class=" d-none d-xl-table-cell"><small>Supplier</small></th>
                            <th class="text-right"><small>Options</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($assets as $asset)
                            <tr>
                                <td>{{ $asset->name }}<br><small class="d-none d-md-inline-block">{{ $asset->model->name ?? 'No Model'}}<</small></td>
                                <td class="text-center" data-sort="{{ $asset->location->name ?? 'Unnassigned'}}">
                                    @if(isset($asset->location->photo->path))
                                        '<img src="{{ asset($asset->location->photo->path)}}" height="30px" alt="{{$asset->location->name}}" title="{{ $asset->location->name ?? 'Unnassigned'}}"/>'
                                    @else
                                        {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($asset->location->icon ?? '#666').'">'
                                            .strtoupper(substr($asset->location->name ?? 'u', 0, 1)).'</span>' !!}
                                    @endif
                                </td>
                                <td>{{ $asset->asset_tag }}</td>
                                <td class="text-center d-none d-xl-table-cell">{{ $asset->model->manufacturer->name ?? 'N/A' }}</td>
                                <td class="d-none d-md-table-cell" data-sort="{{ strtotime($asset->purchased_date)}}">{{ \Carbon\Carbon::parse($asset->purchased_date)->format('d/m/Y')}}</td>
                                <td class="text-center  d-none d-xl-table-cell">
                                    £{{ $asset->purchased_cost }}
                                    @if($asset->model)
                                    <br>
                                    @php
                                    $eol = Carbon\Carbon::parse($asset->purchased_date)->addYears($asset->model->depreciation->years);
                                    if($eol->isPast()){
                                        $dep = 0;
                                    }else{
                                        $age = Carbon\Carbon::now()->floatDiffInYears($asset->purchased_date);
                                        $percent = 100 / $asset->model->depreciation->years;
                                        $percentage = floor($age)*$percent;
                                        $dep = $asset->purchased_cost * ((100 - $percentage) / 100);
                                    }
                                    @endphp
                                    <small>(*£{{ number_format($dep, 2)}})</small>
                                    @endif
                                </td>
                                <td class="text-center d-none d-xl-table-cell">{{$asset->supplier->name ?? "N/A"}}</td>
                                <td class="text-right">
                                    <div class="dropdown no-arrow">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Asset Options:</div>
                                            <a href="{{ route('assets.restore', $asset->id) }}"
                                                class="dropdown-item">Restore</a>
                                            <form class="d-block" id="form{{$asset->id}}" action="{{ route('assets.remove', $asset->id) }}" method="POST">   
                                                @csrf
                                                @can('delete', $asset)
                                                <a class="deleteBtn dropdown-item" href="#"
                                                    data-id="{{$asset->id}}">Delete</a>
                                                @endcan
                                            </form>
                                        </div>
                                        </div>
                                    </div>
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
    <div class="modal fade bd-example-modal-lg" id="removeAssetModal" tabindex="-1" role="dialog"
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
                    <small class="text-coral">**Warning this is permanent. All assets assigned to this asset will be
                        set to Null.</small>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-coral" type="button" id="confirmBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script>

        $('.deleteBtn').click(function() {
            $('#asset-id').val($(this).data('id'));
            //showModal
            $('#removeAssetModal').modal('show');
        });

        $('#confirmBtn').click(function() {
        var form = '#'+'form'+$('#asset-id').val();
        $(form).submit();
        });

        $(document).ready( function () {
            $('#assetsTable').DataTable({
                "autoWidth": false,
                "pageLength": 25,
                "columnDefs": [ {
                "targets": [7],
                "orderable": false
                    }],
                "order": [[ 1, "asc"]],
            });
        });
    </script>
@endsection
