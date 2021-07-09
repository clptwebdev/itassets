@extends('layouts.app')

@section('css')
<link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet" />
@endsection

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Status Fields</h1>
    <div>
        <a href="#" data-toggle="modal" data-target="#addStatusModal"
            class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Add New Status</a>
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
    <p class="mb-4">Below are the different categories of all the different assets statuses stored in the management system. Each
        has displays the amount of different assets that are assigned the status.</p>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="categoryTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-center"><input type="checkbox"></th>
                            <th class="col-4">Name</th>
                            <th>Deployable</th>
                            <th>Assets</th>
                            <th>Components</th>
                            <th>Consumables</th>
                            <th>Accessories</th>
                            <th>Miscellaneous</th>
                            <th class="text-right col-3">Options</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center"><input type="checkbox"></th>
                            <th>Name</th>
                            <th>Deployable</th>
                            <th>Assets</th>
                            <th>Components</th>
                            <th>Consumables</th>
                            <th>Accessories</th>
                            <th>Miscellaneous</th>
                            <th class="text-right">Options</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php $statuses = App\Models\Status::all();?>
                        @foreach($statuses as $status)
                        <tr>
                            <td class="text-center"><input type="checkbox"></td>
                            <td>{{ $status->name }}</td>
                            <td class="text-center">@if($status->deployable == 1){!! '<i class="fas fa-check text-success"></i>'!!}@else{!!'<i class="fas fa-times text-danger"></i>'!!}@endif</td>
                            <td class="text-center">
                                @php
                                    $assets = auth()->user()->location_assets()->statusFilter([$status->id]);
                                @endphp
                                {{ $assets->count() }}
                            </td>
                            <td class="text-center">N/A</td>
                            <td class="text-center">N/A</td>
                            <td class="text-center">N/A</td>
                            <td class="text-center">N/A</td>
                            <td class="text-right">
                                <a href="{{ route('status.show', $status->id) }}"
                                    class="btn-sm btn-secondary text-white"><i class="far fa-eye"></i>
                                    View</a>&nbsp;
                                <a href="#" class="btn-sm btn-secondary text-white updateBtn"
                                    data-id="{{$status->id}}" data-name="{{ $status->name}}"
                                    data-route="{{ route('status.update', $status->id)}}" data-deploy="{{$status->deployable}}"><i
                                        class="fas fa-pencil-alt"></i></a>&nbsp;
                                <a class="btn-sm btn-danger text-white deleteBtn" href="#"
                                    data-route="{{ route('status.destroy', $status->id)}}"><i
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
<!-- Create Modal-->
<div class="modal fade bd-example-modal-lg" id="addStatusModal" tabindex="-1" role="dialog"
    aria-labelledby="addStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStatusModalLabel">Create New Status
                </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route('status.store')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <p>Please enter the name of your category.</p>
                        <input class="form-control" name="name" id="name" type="text" placeholder="Category Name">
                    </div>
                    <div class="form-group">
                        <p>Will the assets that have this status be deployable?</p>
                        <input type="radio" id="deployable_yes" name="deployable" value="1">
                        <label for="deployable_yes">Yes</label><br>
                        <input type="radio" id="deployable_no" name="deployable" value="0">
                        <label for="deployable_no">No</label>
                    </div>
                    <small class="text-info">**You will be able to assign this Status to any assets on the system. These
                        can act as a filter.</small>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" type="button" id="confirmBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- update Modal-->
<div class="modal fade bd-example-modal-lg" id="updateStatusModal" tabindex="-1" role="dialog"
    aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateCategoryModalLabel">Change Status</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="updateForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="form-group">

                        <p>Please enter the name of your category.</p>
                        <input class="form-control" name="name" id="update_name" type="text" value="">
                    </div>
                    <div class="form-group">
                        <p>Will the assets that have this status be deployable?</p>
                        <input type="radio" id="update_deployable_yes" name="deployable" value="1">
                        <label for="deployable_yes">Yes</label><br>
                        <input type="radio" id="update_deployable_no" name="deployable" value="0">
                        <label for="deployable_no">No</label>
                    </div>
                    <small class="text-info">**You will be able to assign categories to any assets on the system. These
                        can act as a filter.</small>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" type="button" id="confirmBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal-->
<div class="modal fade bd-example-modal-lg" id="removeStatusModal" tabindex="-1" role="dialog"
    aria-labelledby="removeStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeStatusModalLabel">Are you sure you want to delete this Status?
                </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <input id="supplier-id" type="hidden" value="">
                <p>Select "Delete" to remove this status from the system.</p>
                <small class="text-danger">**Warning this is permanent. The status will be unassigned from assets, any
                    assets with just this status will have the status set to null.</small>
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
        $('#removeStatusModal').modal('show');
    });

    $('#confirmBtn').click(function() {
        $('#deleteForm').submit();
    });

    $('.updateBtn').click(function(){
        var val = $(this).data('id');
        var deployable = $(this).data('deploy');
        if(deployable == 1){ 
            document.getElementById("update_deployable_yes").checked = true;
        }else{ 
            document.getElementById("update_deployable_no").checked = true; 
        }
        var name = $(this).data('name');
        var route = $(this).data('route');
        $('#update_name').val(name);
        $('#updateForm').attr('action', route); 
        $('#updateStatusModal').modal('show');
    });
    
    

    $(document).ready( function () {
        $('#categoryTable').DataTable({
            "columnDefs": [ {
                "targets": [0, 5],
                "orderable": false,
            } ],
            "order": [[ 1, "asc"]]
        });
    } );
</script>
@endsection