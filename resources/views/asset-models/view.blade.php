@extends('layouts.app')

@section('title', 'Asset Models')

@section('css')
<link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet" />
@endsection

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Asset Models</h1>
    <div>
        <a href="{{ route('asset-models.create')}}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Add New Asset Modal</a>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
    </div>
</div>

@if(session('danger_message'))
<div class="alert alert-danger"> {{ session('danger_message')}} </div>
@endif

@if(session('success_message'))
<div class="alert alert-success"> {{ session('success_message')}} </div>
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
                            <th>ID</th>
                            <th>Name</th>
                            <th>Manufacturer</th>
                            <th>Model No:</th>
                            <th>Deprciation</th>
                            <th class="text-center">Options</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Manufacturer</th>
                            <th>Model No:</th>
                            <th>Depreciation</th>
                            <th class="text-center">Options</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php $models = App\Models\AssetModel::all();?>
                        @foreach($models as $model)
                        <tr>
                            <td>{{ $model->id }}</td>
                            <td>{{ $model->name }}</td>
                            <td>{{ $model->manufacturer->name}}</td>
                            <td>{{ $model->model_no }}</td>
                            <td>{{ $model->depreciation->name ?? 'No Depreciation Set' }}</td>
                            <td class="text-center">
                                <form id="form{{$model->id}}" action="{{ route('asset-models.destroy', $model->id) }}"
                                    method="POST">
                                    <a href="{{ route('asset-models.show', $model->id) }}"
                                        class="btn-sm btn-secondary text-white"><i class="far fa-eye"></i>
                                        View</a>&nbsp;
                                    <a href="{{route('asset-models.edit', $model->id) }}"
                                        class="btn-sm btn-secondary text-white"><i
                                            class="fas fa-pencil-alt"></i></a>&nbsp;

                                    @csrf
                                    @method('DELETE')
                                    <a class="btn-sm btn-danger text-white deleteBtn" href="#"
                                        data-id="{{$model->id}}"><i class=" fas fa-trash"></i></a>
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
            <h4>Help with Suppliers</h4>
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
                <p>Select "Delete" to remove this Asset Model from the system.</p>
                <small class="text-danger">**Warning this is permanent. This will unassign any Assets that have this model. </small>
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
            $('#model-id').val($(this).data('id'))
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
                    "targets": [3,5],
                    "orderable": false,
                } ],
                "order": [[ 1, "asc"]]
            });
        } );
</script>
@endsection