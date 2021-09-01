@extends('layouts.app')

@section('title', 'Asset Fields')

@section('css')
<link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet" />
@endsection

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Custom Fields</h1>
    <div>
        <a href="{{ route('fields.create')}}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Add New Custom Field</a>
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
                <table id="fieldsetTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th class="col-3"><small>Name</small></th>
                            <th class="col-1"><small>Required</small></th>
                            <th class="col-1"><small>Type</small></th>
                            <th class="col-1"><small>Format</small></th>
                            <th class="col-5"><small>Fielsets</small></th>
                            <th class="text-right col-1">Options</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="col-3"><small>Name</small></th>
                            <th class="col-1"><small>Required</small></th>
                            <th class="col-1"><small>Type</small></th>
                            <th class="col-1"><small>Format</small></th>
                            <th class="col-5"><small>Fielsets</small></th>
                            <th class="text-right col-1">
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($fields as $field)
                        <tr>
                            <td class="text-left">{{ $field->name }}</td>
                            <td class="text-center">@if($field->required == 1){!! '<i class="fas fa-check text-success"></i>'!!}@else{!!'<i
                                    class="fas fa-times text-danger"></i>'!!}@endif</td>
                            <td class="text-center">{{ $field->type }}</td>
                            <td class="text-center">{{ $field->format }}</td>
                            <td>
                                @foreach($field->fieldsets as $fieldset)
                                 {!! '<small class="d-inline-block bg-secondary text-light p-2 m-1 rounded">'.$fieldset->name.'</small>' !!}
                                @endforeach
                            </td>
                            <td class="text-right">
                                
                                <div class="dropdown no-arrow">
                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                        id="dropdownMenuLink"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div
                                        class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                        aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Asset Options:</div>
                                        <a href="{{route('fields.edit', $field->id) }}" class="dropdown-item">Edit</a>
                                        <a class="dropdown-item" href="#" data-route="{{ route('fields.destroy', $field->id)}}">Delete</a>
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
<div class="modal fade bd-example-modal-lg" id="removeCategoryModal" tabindex="-1" role="dialog"
    aria-labelledby="removeCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeCategoryModalLabel">Are you sure you want to delete this Category?
                </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <input id="field-id" type="hidden" value="">
                <p>Select "Delete" to remove this field from the system.</p>
                <small class="text-danger">**Warning this is permanent. This will also remove all the linked field values in the assets </small>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" type="button" id="confirmBtn">Delete</button>
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
        $('#removeCategoryModal').modal('show');
    });
    
    $('#confirmBtn').click(function() {
        $('#deleteForm').submit();
    });

    $(document).ready( function () {
        $('#fieldsetTable').DataTable({
            "columnDefs": [ {
                "targets": [5],
                "orderable": false,
            } ],
            "order": [[ 0, "asc"]]
        });
    } );
</script>
@endsection