@extends('layouts.app')

@section('title', 'View Categories')

@section('css')
<link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet" />
@endsection

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Categories</h1>
    <div>
        <a href="#" data-toggle="modal" data-target="#addCategoryModal" class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Add New Category</a>{{--
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
    </div>
</div>

@if(session('danger_message'))
<div class="alert alert-danger"> {{ session('danger_message')}} </div>
@endif

@if(session('success_message'))
<div class="alert alert-success"> {{ session('success_message')}} </div>
@endif

<section>
    <p class="mb-4">Below are the different categories of all the different assets stored in the management system. Each has
        displays the amount of different assets that are assigned the category.</p>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="categoryTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th class="col-6"><small>Name</small></th>
                            <th class="text-center col-1"><small>Assets</small></th>
                            <th class="text-center col-1"><small>Accessories</small></th>
                            <th class="text-center col-1"><small>Components</small></th>
                            <th class="text-center col-1"><small>Consumables</small></th>
                            <th class="text-center col-1"><small>Miscellaneous</small></th>
                            <th class="text-center col-1"><small>Options</small></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th><small>Name</small></th>
                            <th class="text-center"><small>Assets</small></th>
                            <th class="text-center"><small>Accessories</small></th>
                            <th class="text-center"><small>Components</small></th>
                            <th class="text-center"><small>Consumables</small></th>
                            <th class="text-center"><small>Miscellaneous</small></th>
                            <th class="text-center"><small>Options</small></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td class="text-center">
                                {{ $category->assets()->locationFilter($locations->pluck('id'))->count()}}
                            </td>
                            <td class="text-center">
                                {{ $category->accessories()->locationFilter($locations->pluck('id'))->count()}}
                            </td>
                            <td class="text-center">
                                {{ $category->components()->locationFilter($locations->pluck('id'))->count()}}
                            </td>
                            <td class="text-center">
                                {{ $category->consumables()->locationFilter($locations->pluck('id'))->count()}}
                            </td>
                            <td class="text-center">
                                {{ $category->miscellanea()->locationFilter($locations->pluck('id'))->count()}}
                            </td>
                            <td class="text-right">
                                <div class="dropdown no-arrow">
                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenu{{$category->id}}Link"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenu{{$category->id}}Link">
                                        <div class="dropdown-header">Category Options:</div>
                                        @can('update', $category)
                                            <a href="#" class="dropdown-item updateBtn"
                                        data-id="{{$category->id}}" data-name="{{ $category->name}}" data-route="{{ route('category.update', $category->id)}}">Edit</a>
                                        @endcan
                                        @can('delete', $category)
                                            <a class="dropdown-item deleteBtn" href="#" data-route="{{ route('category.destroy', $category->id)}}">Delete</a>
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
    <div class="card shadow mb-3">
        <div class="card-body">
            <h4>Help with Category's</h4>
                <p>Click <a href="{{route("documentation.index").'#collapseSeventeenCategories'}}">here</a> for the Documentation on Categories on Adding and Removing!</p>
        </div>
    </div>
</section>

@endsection

@section('modals')
<!-- Create Modal-->
<div class="modal fade bd-example-modal-lg" id="addCategoryModal" tabindex="-1" role="dialog"
    aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Create New Category
                </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route('category.store')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <input id="supplier-id" type="hidden" value="">
                    <p>Please enter the name of your category.</p>
                    <input class="form-control" name="name" id="name" type="text" placeholder="Category Name">
                    <small class="text-info">**You will be able to assign categories to any assets on the system. These can act as a filter.</small>
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
<div class="modal fade bd-example-modal-lg" id="updateCategoryModal" tabindex="-1" role="dialog"
    aria-labelledby="updateCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateCategoryModalLabel">Change Category Name
                </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="updateForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <input id="supplier-id" type="hidden" value="">
                    <p>Please enter the name of your category.</p>
                    <input class="form-control" name="name" id="name" type="text" value="">
                    <small class="text-info">**You will be able to assign categories to any assets on the system. These
                        can act as a filter.</small>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-coral" type="button" id="confirmBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
                <input id="supplier-id" type="hidden" value="">
                <p>Select "Delete" to remove this supplier from the system.</p>
                <small class="text-danger">**Warning this is permanent. The category will be unassigned from assets, any assets with just this category will have the category set to null.</small>
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
        $('#removeCategoryModal').modal('show');
    });

    $('#confirmBtn').click(function() {
        $('#deleteForm').submit();
    });

    $('.updateBtn').click(function(){
        var val = $(this).data('id');
        var name = $(this).data('name');
        var route = $(this).data('route');
        $('[name="name"]').val(name);
        $('#updateForm').attr('action', route);
        $('#updateCategoryModal').modal('show');
    });



    $(document).ready( function () {
        $('#categoryTable').DataTable({
            "columnDefs": [ {
                "targets": [6],
                "orderable": false,
            } ],
            "order": [[ 0, "asc"]]
        });
    } );
</script>
@endsection
