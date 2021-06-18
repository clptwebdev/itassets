@extends('layouts.app')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Assets</h1>
        <div>
            <a href="{{ route('assets.create')}}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                    class="fas fa-plus fa-sm text-white-50"></i> Add New Asset(s)</a>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
            <a href="export" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50"></i> Generate Csv</a>
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
                                        '<img src="{{ asset($asset->location->photo->path)}}" height="30px" alt="{{$asset->location->name}}"/>'
                                    @else
                                        {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($asset->location->icon ?? '#666').'">'
                                            .strtoupper(substr($asset->location->name ?? 'u', 0, 1)).'</span>' !!}
                                    @endif
                                </td>
                                <td>{{ $asset->asset_tag }}</td>
                                <td class="text-center">{{ $asset->model->manufacturer->name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($asset->purchased_date)->format('d/m/Y')}}</td>
                                <td class="text-center">
                                    £{{ $asset->purchased_cost }}<br>
                                    <?php $age = Carbon\Carbon::now()->floatDiffInYears($asset->purchased_date);
                                    $percentage = floor($age)*33.333;
                                    $dep = $asset->purchased_cost * ((100 - $percentage) / 100);?>
                                    <small>(*£{{ number_format($dep, 2)}})</small>
                                </td>
                                <td class="text-center">{{ \Illuminate\Support\Str::of($asset->supplier->name)->limit(5) }}</td>
                                <?php $warranty_end = \Carbon\Carbon::parse($asset->purchased_date)->addMonths($asset->warranty);?>
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
                                <td class="text-center">
                                    <form id="form{{$asset->id}}" action="{{ route('assets.destroy', $asset->id) }}"
                                          method="POST">
                                        <a href="{{ route('assets.show', $asset->id) }}"
                                           class="btn-sm btn-secondary text-white"><i class="far fa-eye"></i></a>&nbsp;
                                        <a href="{{route('assets.edit', $asset->id) }}"
                                           class="btn-sm btn-secondary text-white"><i class="fas fa-pencil-alt"></i></a>&nbsp;

                                        @csrf
                                        @method('DELETE')
                                        <a class="btn-sm btn-danger text-white deleteBtn" href="#"
                                           data-id="{{$asset->id}}"><i class=" fas fa-trash"></i></a>
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
    <script>
        $('.deleteBtn').click(function() {
        $('#supplier-id').val($(this).data('id'))
        //showModal
        $('#removeSupplierModal').modal('show')
        });

        $('#confirmBtn').click(function() {
        var form = '#'+'form'+$('#supplier-id').val();
        $(form).submit();
        });

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
