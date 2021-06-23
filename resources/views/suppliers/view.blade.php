@extends('layouts.app')

@section('css')
<link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet" />
@endsection

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Suppliers</h1>
    <div>
        <a href="{{ route('supplier.create')}}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Add New Supplier</a>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
        <a href="exportsuppliers" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Download Csv</a>
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
                    <table id="suppliersTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center"><input type="checkbox"></th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Telephone</th>
                                <th>Email</th>
                                <th class="text-center">Options</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th class="text-center"><input type="checkbox"></th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Tel</th>
                                <th>Email</th>
                                <th class="text-center">Options</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php $suppliers = App\Models\Supplier::all();?>
                            @foreach($suppliers as $supplier)
                            <tr>
                                <td class="text-center"><input type="checkbox"></td>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->city }}</td>
                                <td>{{ $supplier->telephone }}</td>
                                <td>{{ $supplier->email }}</td>
                                <td class="text-center">
                                    <form id="form{{$supplier->id}}" action="{{ route('supplier.destroy', $supplier->id) }}" method="POST">
                                    <a href="{{ route('supplier.show', $supplier->id) }}" class="btn-sm btn-secondary text-white"><i class="far fa-eye"></i> View</a>&nbsp;
                                    <a href="{{route('supplier.edit', $supplier->id) }}" class="btn-sm btn-secondary text-white"><i class="fas fa-pencil-alt"></i></a>&nbsp;

                                        @csrf
                                        @method('DELETE')
                                        <a class="btn-sm btn-danger text-white deleteBtn" href="#" data-id="{{$supplier->id}}"><i class=" fas fa-trash"></i></a>
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
    <div class="modal fade bd-example-modal-lg" id="removeSupplierModal" tabindex="-1" role="dialog"
        aria-labelledby="removeSupplierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeSupplierModalLabel">Are you sure you want to delete this Supplier?
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input id="supplier-id" type="hidden" value="">
                    <p>Select "Delete" to remove this supplier from the system.</p>
                    <small class="text-danger">**Warning this is permanent. All assets assigned to this supplier will be set to Null.</small>
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
            $('#suppliersTable').DataTable({
                "columnDefs": [ {
                    "targets": [0, 5],
                    "orderable": false,
                } ],
                "order": [[ 1, "asc"]]
            });
        } );
    </script>
    @endsection
