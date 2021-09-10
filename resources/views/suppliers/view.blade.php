@extends('layouts.app')
@section('title', 'View Suppliers')
@section('css')
<link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet" />
@endsection

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Suppliers</h1>
    <div>
        @can('create', \App\Models\Supplier::class)
        <a href="{{ route('suppliers.create')}}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                class="fas fa-plus fa-sm text-white-50"></i> Add New Supplier</a>
        @endcan
        @can('viewAny', \App\Models\Supplier::class)
        <a href="{{ route('suppliers.pdf')}}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm loading"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
        <a href="exportsuppliers" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm loading"><i
                class="fas fa-download fa-sm text-white-50"></i> Export</a>
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
                    <table id="suppliersTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th><small>Name</small></th>
                                <th><small>Location</small></th>
                                <th><small>Telephone</small></th>
                                <th><small>Email</small></th>
                                <th class="text-center d-none d-xl-table-cell"><small>Assets</small></th>
                                <th class="text-center d-none d-xl-table-cell"><small>Accessories</small></th>
                                <th class="text-center d-none d-xl-table-cell"><small>Components</small></th>
                                <th class="text-center d-none d-xl-table-cell"><small>Consumables</small></th>
                                <th class="text-center d-none d-xl-table-cell"><small>Miscellaneous</small></th>
                                <th class="text-center"><small>Options</small></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th><small>Name</small></th>
                                <th><small>Location</small></th>
                                <th><small>Telephone</small></th>
                                <th><small>Email</small></th>
                                <th class="text-center d-none d-xl-table-cell"><small>Assets</small></th>
                                <th class="text-center d-none d-xl-table-cell"><small>Accessories</small></th>
                                <th class="text-center d-none d-xl-table-cell"><small>Components</small></th>
                                <th class="text-center d-none d-xl-table-cell"><small>Consumables</small></th>
                                <th class="text-center d-none d-xl-table-cell"><small>Miscellaneous</small></th>
                                <th class="text-center"><small>Options</small></th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php $suppliers = App\Models\Supplier::all();?>
                            @foreach($suppliers as $supplier)
                            <tr>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->city }}</td>
                                <td>{{ $supplier->telephone }}</td>
                                <td>{{ $supplier->email }}</td>
                                <td class="text-center d-none d-xl-table-cell">{{ $supplier->asset->count() }}                                </td>
                                <td class="text-center d-none d-xl-table-cell">{{$supplier->accessory->count() ?? "N/A"}}</td>
                                <td class="text-center d-none d-xl-table-cell">{{$supplier->component->count() ?? "N/A"}}</td>
                                <td class="text-center d-none d-xl-table-cell">{{$supplier->consumable->count() ?? "N/A"}}</td>
                                <td class="text-center d-none d-xl-table-cell">{{$supplier->miscellanea->count() ?? "N/A"}}</td>
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
                                            <div class="dropdown-header">Supplier Options:</div>
                                            @can('view', $supplier)
                                            <a href="{{ route('suppliers.show', $supplier->id) }}"
                                               class="dropdown-item">View</a>
                                            @endcan
                                            @can('update', $supplier)
                                                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="dropdown-item">Edit</a>
                                            @endcan
                                            @can('delete', $supplier)
                                                <form id="form{{$supplier->id}}" action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="deleteBtn dropdown-item" href="#"
                                                       data-id="{{$supplier->id}}">Delete</a>
                                                </form>
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
