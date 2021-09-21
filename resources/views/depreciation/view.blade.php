@extends('layouts.app')

@section('title', 'Drepreciation Models')

@section('title', '')

@section('css')
<link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet" />
@endsection

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Depreciation</h1>
    <div>
        <a href="#" data-toggle="modal" data-target="#addStatusModal"
            class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Add New Depreciation</a>
    </div>
</div>

@if(session('danger_message'))
<div class="alert alert-danger"> {{ session('danger_message')}} </div>
@endif

@if(session('success_message'))
<div class="alert alert-success"> {{ session('success_message')}} </div>
@endif

<section>
    <p class="mb-4">Below are the different depreciation models. </p>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="depTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th class="col-4"><small>Name</small></th>
                            <th class="col-1 text-center"><small>Months</small></th>
                            <th class="col-6">Models</th>
                            <th class="text-right col-1">Options</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="col-4"><small>Name</small></th>
                            <th class="col-1 text-center"><small>Months</small></th>
                            <th class="col-6"><small>Models</small></th>
                            <th class="text-right col-1"><small>Options</small></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($depreciation as $dep)
                        <tr>
                            <td>{{ $dep->name }}</td>
                            <td class="text-center">{{$dep->years * 12}}</td>
                            <td class="text-left   ">
                                @foreach($dep->models->take(10) as $model)
                                    <small class="bg-secondary rounded p-1 m-1 text-white">{{$model->name}}</small>
                                @endforeach
                                <small class="bg-light border border-secondary rounded p-1 m-1 text-secondary showBtn pointer" data-id="{{$dep->id}}" data-name="{{$dep->name}}" data-route="{{ route('depreciation.show', $dep->id)}}"><i class="fas fa-ellipsis-h"></i></small>
                            </td>                            
                            <td class="text-right">
                                <div class="dropdown no-arrow">
                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenu{{$dep->id}}Link"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenu{{$dep->id}}Link">
                                        <div class="dropdown-header">Asset Options:</div>
                                        <a href="#" class="dropdown-item updateBtn" data-id="{{$dep->id}}"
                                        data-route="{{ route('depreciation.update', $dep->id)}}" data-name="{{$dep->name}}" data-years="{{$dep->years}}">Edit</a>
                                        <a class="dropdown-item deleteBtn" href="#" data-route="{{ route('depreciation.destroy', $dep->id)}}">Delete</a>
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
            <form action="{{ route('depreciation.store')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <p>Please enter the name of your depreciation model.</p>
                        <input class="form-control" name="name" id="name" type="text" placeholder="Dep. Name">
                    </div>
                    <div class="form-group">
                        <label for="years">Depreciation Model Duration*:</p>
                        <input class="form-control" type="number" id="year" name="years" value="3">
                        <small class="text-info">This is amount of years the depreciation will be spread. For example a 3 year depreciation model will deduct 33% of its value each year.
                            Whereas a 4 year model will deduct 25% each calender year from it's purchase date.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-coral" type="button" id="confirmBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- update Modal-->
<div class="modal fade bd-example-modal-lg" id="updateDepModal" tabindex="-1" role="dialog"
    aria-labelledby="updateDepModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateDepModalLabel">Update Depreciation Model</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="updateForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="form-group">
                        <p>Please enter the name of your depreciation model.</p>
                        <input class="form-control" name="name" id="update_name" type="text" placeholder="Dep. Name">
                    </div>
                    <div class="form-group">
                        <label for="years">Depreciation Model Duration*:</p>
                        <input class="form-control" type="number" id="update_years" name="years" value="3">
                        <small class="text-info">This is amount of years the depreciation will be spread. For example a 3 year depreciation model will deduct 33% of its value each year.
                            Whereas a 4 year model will deduct 25% each calender year from it's purchase date.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-coral" type="button" id="confirmBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- show Modal-->
<div class="modal fade bd-example-modal-lg" id="showDepModal" tabindex="-1" role="dialog"
    aria-labelledby="showDepModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showDepModalLabel"></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="showDepModels">
            </div>
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
        $('#removeStatusModal').modal('show');
    });

    $('#confirmBtn').click(function() {
        $('#deleteForm').submit();
    });

    $('.updateBtn').click(function(){
        var val = $(this).data('id');
        var name = $(this).data('name');
        var route = $(this).data('route');
        var years = $(this).data('years');
        $('#update_name').val(name);
        $('#update_years').val(years);
        $('#updateForm').attr('action', route); 
        $('#updateDepModal').modal('show');
    });

    $('.showBtn').click(function(){
        var name = $(this).data('name');
        var route = $(this).data('route');
        $('#showDepModalLabel').html(name);
        $.ajax({
            url: route, 
            type: 'GET',
            success: function(response){
                let html = "";
                Object.entries(response).forEach(entry => {
                    const [key, value] = entry;
                    var string = `<small class="bg-secondary rounded p-1 m-1 text-white">${value['name']}</small>`
                    html = html.concat(string);
                });
                $('#showDepModels').html(html);
        $('#showDepModal').modal('show');
            },
        });

        
    }); 

    $(document).ready( function () {
        $('#depTable').DataTable({
            "columnDefs": [ {
                "targets": [2,3],
                "orderable": false,
            } ],
            "order": [[ 0, "asc"]]
        });
    } );
</script>
@endsection