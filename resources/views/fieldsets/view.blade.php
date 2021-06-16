@extends('layouts.app')

@section('css')
<link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet" />
@endsection

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Asset Model Custom Fieldsets</h1>
    <div>
        <a href="{{ route('fieldsets.create')}}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Add New Custom Fieldset</a>
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
                <table id="fieldsetTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center col-auto"><input type="checkbox"></th>
                            <th class="col-3">Name</th>
                            <th class="col-2">Fields</th>
                            <th class="col-4">Assets</th>
                            <th class="col-2 text-right">Options</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center"><input type="checkbox"></th>
                            <th>Name</th>
                            <th>Fields</th>
                            <th>Assets</th>
                            <th class="text-right">Options</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($fieldsets as $fieldset)
                        <tr>
                            <td class="text-center"><input type="checkbox"></td>
                            <td>{{ $fieldset->name }}</td>
                            <td>{{ $fieldset->fields->count()}}</td>
                            <td>
                                <small class="p-1 bg-danger rounded text-white">HP Pro Desk 10.1</small>
                                <small class="p-1 bg-primary rounded text-white">Surface Pro 7</small>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('fieldsets.show', $fieldset->id) }}" class="btn-sm btn-secondary text-white"><i class="far fa-eye"></i> View</a>&nbsp;
                                <a href="{{route('fieldsets.edit', $fieldset->id) }}"
                                    class="btn-sm btn-secondary text-white"><i
                                        class="fas fa-pencil-alt"></i></a>&nbsp;
                                <a class="btn-sm btn-danger text-white deleteBtn" href="#" data-route="{{ route('fieldsets.destroy', $fieldset->id)}}"><i
                                        class=" fas fa-trash"></i></a>
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
        //showModal
        $('#removeFieldsetModal').modal('show');
    });
    
    $('#confirmBtn').click(function() {
        $('#deleteForm').submit();
    });

    $(document).ready( function () {
        $('#fieldsetTable').DataTable({
            "columnDefs": [ {
                "targets": [0, 2, 3],
                "orderable": false,
            } ],
            "order": [[ 1, "asc"]]
        });
    } );
</script>
@endsection