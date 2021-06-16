@extends('layouts.app')

@section('css')
<link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet" />
@endsection

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Categories</h1>
    <div>
        <a href="#" data-toggle="modal" data-target="#addCategoryModal" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Add New Category</a>
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
    <p class="mb-4">Below are the different categories of all the different assets stored in the management system. Each has
        displays the amount of different assets that are assigned the category.</p>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="categoryTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-center"><input type="checkbox"></th>
                            <th class="col-4">Name</th>
                            <th>Assets</th>
                            <th>Components</th>
                            <th>Consumables</th>
                            <th>Accessories</th>
                            <th>Miscellaneous</th>
                            <th class="text-center col-4">Options</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th class="text-center"><input type="checkbox"></th>
                            <th>Name</th>
                            <th>Assets</th>
                            <th>Components</th>
                            <th>Consumables</th>
                            <th>Accessories</th>
                            <th>Miscellaneous</th>
                            <th class="text-center">Options</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php $categories = App\Models\Category::all();?>
                        @foreach($categories as $category)
                        <tr>
                            <td class="text-center"><input type="checkbox"></td>
                            <td>{{ $category->name }}</td>
                            <td>123</td>
                            <td>23</td>
                            <td>64</td>
                            <td>12</td>
                            <td>1</td>
                            <td class="text-center">
                                <a href="{{ route('category.show', $category->id) }}"
                                    class="btn-sm btn-secondary text-white"><i class="far fa-eye"></i>
                                    View</a>&nbsp;
                                <a href="#" class="btn-sm btn-secondary text-white updateBtn" 
                                    data-id="{{$category->id}}" data-name="{{ $category->name}}" data-route="{{ route('category.update', $category->id)}}"><i
                                        class="fas fa-pencil-alt"></i></a>&nbsp;
                                <a class="btn-sm btn-danger text-white deleteBtn" href="#"
                                    data-route="{{ route('category.destroy', $category->id)}}"><i class=" fas fa-trash"></i></a>
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
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" type="button" id="confirmBtn">Save</button>
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
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" type="button" id="confirmBtn">Save</button>
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
                "targets": [0, 5],
                "orderable": false,
            } ],
            "order": [[ 1, "asc"]]
        });
    } );
</script>
@endsection