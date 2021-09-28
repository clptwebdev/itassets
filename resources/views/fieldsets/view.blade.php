@extends('layouts.app')

@section('title', 'Asset Model Fieldsets')

@section('css')
<link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet" />
@endsection

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Asset Model Custom Fieldsets</h1>
    <div>
        <a href="{{ route('fieldsets.create')}}" class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Add New Custom Fieldset</a>
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
                <table id="fieldsetTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th class="col-3"><small>Name</small></th>
                            <th class="col-1"><small>Fields</small></th>
                            <th class="col-7"><small>Assets</small></th>
                            <th class="col-1 text-right"><small>Options</small></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="col-3"><small>Name</small></th>
                            <th class="col-1"><small>Fields</small></th>
                            <th class="col-7"><small>Assets</small></th>
                            <th class="col-1 text-right"><small>Options</small></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($fieldsets as $fieldset)
                        <tr>
                            <td>{{ $fieldset->name }}</td>
                            <td>{{ $fieldset->fields->count()}}</td>
                            <td>
                                @foreach($fieldset->models as $model)
                                <small class="p-1 bg-secondary rounded text-white">{{ $model->name }}</small>
                                @endforeach
                            </td>
                            <td class="text-right">
                                <div class="dropdown no-arrow">
                                    <a class="btn btn-lilac dropdown-toggle" href="#" role="button"
                                        id="dropdownMenuLink"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div
                                        class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                        aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Asset Options:</div>
                                        @can('update', $fieldset)
                                        <a href="{{ route('fieldsets.edit', $fieldset->id) }}" class="dropdown-item">Edit</a>
                                        @endcan
                                        @can('delete', $fieldset)
                                        <a class="dropdown-item deleteBtn" href="#" data-route="{{ route('fieldsets.destroy', $fieldset->id)}}"">Delete</a>
                                        @endcan
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

</section>

@endsection

@section('modals')
<!-- Delete Modal-->
<div class="modal fade bd-example-modal-lg" id="removeFieldsetModal" tabindex="-1" role="dialog"
    aria-labelledby="removeFieldsetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeFieldsetModalLabel">Are you sure you want to delete this Fieldset?
                </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <input id="supplier-id" type="hidden" value="">
                <p>Select "Delete" to remove this Fieldset from the system.</p>
                <small class="text-danger">**Warning this is permanent. The fieldset will be unassigned from assets models, any
                    assets with just this fieldset will have their fieldset set to null.</small>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-coral" type="button" id="confirmBtn">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
    $('.deleteBtn').click(function() {
        $('#deleteForm').attr('action', $(this).data('route'));
        //showModal
        $('#removeFieldsetModal').modal('show');
    });

    $('#confirmBtn').click(function() {
        $('#deleteForm').submit();
    });

    $(document).ready( function () {
        $('#fieldsetTable').DataTable({
            "columnDefs": [ {
                "targets": [2, 3],
                "orderable": false,
            } ],
            "order": [[ 0, "asc"]]
        });
    } );
</script>
@endsection
