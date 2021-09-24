@extends('layouts.app')

@section('title', 'Asset Models')

@section('css')
<link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet" />
@endsection

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Asset Models</h1>
    <div>
        @can('create', \App\Models\AssetModel::class)
        <a href="{{ route('asset-models.create')}}" class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Add New Asset Modal</a>
        @endcan
        @can('viewAny', \App\Models\AssetModel::class)
        <a href="{{ route('asset-model.pdf')}}" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm loading"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
        @endcan
    </div>
</div>

@if(session('danger_message'))
<div class="alert alert-danger"> {!! session('danger_message')!!} </div>
@endif

@if(session('success_message'))
<div class="alert alert-success"> {!! session('success_message')!!} </div>
@endif

<section>
    <p class="mb-4">Below are the different suppliers of the assets stored in the management system. Each has
        different options and locations can created, updated, and deleted.</p>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="modelsTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th><small>Name</small></th>
                            <th><small>Manufacturer</small></th>
                            <th><small>Model No:</small></th>
                            <th class="text-center"><small>Assets</small></th>
                            <th><small>Depreciation</small></th>
                            <th class="text-right col-1"><small>Options</small></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th><small>Name</small></th>
                            <th><small>Manufacturer</small></th>
                            <th><small>Model No:</small></th>
                            <th class="text-center"><small>Assets</small></th>
                            <th><small>Depreciation</small></th>
                            <th class="text-right"><small>Options</small></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php $models = App\Models\AssetModel::all();?>
                        @foreach($models as $model)
                        <tr>
                            <td>{{ $model->name }}</td>
                            <td>{{ $model->manufacturer->name ?? 'N/A'}}</td>
                            <td>{{ $model->model_no }}</td>
                            <td class="text-center">{{ $model->assets->count() }}</td>
                            <td>{{ $model->depreciation->name ?? 'No Depreciation Set' }}</td>
                            <td class="text-right">
                                <div class="dropdown no-arrow">
                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenu{{$model->id}}Link"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenu{{$model->id}}Link">
                                        <div class="dropdown-header">Asset Model Options:</div>
                                        <a href="{{ route('asset-models.show', $model->id) }}" class="dropdown-item">View</a>
                                        <a href="{{route('asset-models.edit', $model->id) }}" class="dropdown-item">Edit</a>
                                        <form class="d-block" id="form{{$model->id}}" action="{{ route('asset-models.destroy', $model->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <a class="dropdown-item deleteBtn" href="#" data-id="{{$model->id}}" data-count="{{ $model->assets->count()}}">Delete</a>
                                        </form>
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
            <h4>Help with Asset Models</h4>
            <p>This area can be minimised and will contain a little help on the page that the user is currently on.</p>
        </div>
    </div>

</section>

@endsection

@section('modals')
<!-- Delete Modal-->
<div class="modal fade bd-example-modal-lg" id="removeModelModal" tabindex="-1" role="dialog"
    aria-labelledby="removeModelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeModelModalLabel">Are you sure you want to delete this Asset Model?
                </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <input id="model-id" type="hidden" value="">
                <p>There are currently <span id="asset_count">0</span> Asset(s) linked with this Asset Model. These Assets will be unassigned and related data will be lost. 
                    If you wish to continue please Select "Delete" to remove this Asset Model from the system.</p>
                <small class="text-danger">**Warning this is permanent. This will unassign any Assets that have this model. </small>
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
<script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
    $('.deleteBtn').click(function() {
            $('#model-id').val($(this).data('id'))
            $('#asset_count').html($(this).data('count'))
            //showModal
            $('#removeModelModal').modal('show')
        });
        
        $('#confirmBtn').click(function() {
            var form = '#'+'form'+$('#model-id').val();
            $(form).submit();
        });

        $(document).ready( function () {
            $('#modelsTable').DataTable({
                "columnDefs": [ {
                    "targets": [5],
                    "orderable": false,
                } ],
                "order": [[ 0, "asc"]]
            });
        } );
</script>
@endsection