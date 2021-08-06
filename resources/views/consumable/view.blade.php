@extends('layouts.app')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')


    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Consumables</h1>
        <div>
            <a href="{{ route('consumables.create')}}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                    class="fas fa-plus fa-sm text-white-50"></i> Add New Consumable</a>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
            @if($consumables->count() >1)
            <a href="/exportconsumables" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50"></i>Export</a>
            @endif
            <a id="import" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50 fa-text-width"></i> Import Csv</a>
        </div>
    </div>

    @if(session('danger_message'))
        <div class="alert alert-danger"> {{ session('danger_message')}} </div>
    @endif

    @if(session('success_message'))
        <div class="alert alert-success"> {{ session('success_message')}} </div>
    @endif

    <section>
        <p class="mb-4">Below are the different Consumables stored in the management system. Each has
            different options and locations can created, updated, and deleted.</p>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="usersTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Order_no</th>
                            <th>Supplier</th>
                            <th>Purchased Date</th>
                            <th>Purchased Cost</th>
                            <th>Status</th>
                            <th>Warranty</th>
                            <th>Location</th>
                            <th>Manufacturers</th>
                            <th class="text-center">Options</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Order_no</th>
                            <th>Supplier</th>
                            <th>Purchased Date</th>
                            <th>Purchased Cost</th>
                            <th>Status</th>
                            <th>Warranty</th>
                            <th>Location</th>
                            <th>Manufacturers</th>
                            <th class="text-center">Options</th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($consumables as $consumable)

                            <tr>
                                <td>{{$consumable->name}}
                                    <br>
                                    <small>{{$consumable->serial_no}}</small>
                                </td>
                                <td>{{$consumable->order_no}}</td>
                                <td>{{$consumable->supplier->name ?? 'N/A'}}</td>
                                <td>{{\Carbon\Carbon::parse($consumable->purchased_date)->format("d/m/Y")}}</td>
                                <td>{{$consumable->purchased_cost}}</td>
                                <td>{{$consumable->status->name ??'N/A'}}</td>
                                <td>{{$consumable->warranty??"N/A"}}</td>
                                <td>{{$consumable->location->name}}</td>
                                <td>{{$consumable->manufacturer->name ?? "N/A"}}</td>
                                <td class="text-center">
                                    <form id="form{{$consumable->id}}"
                                          action="{{ route('consumables.destroy', $consumable->id) }}" method="POST">
                                        <a href="{{ route('consumables.show', $consumable->id) }}"
                                           class="btn-sm btn-secondary text-white"><i class="far fa-eye"></i>
                                            View</a>&nbsp;
                                        <a href="{{route('consumables.edit', $consumable->id) }}"
                                           class="btn-sm btn-secondary text-white"><i
                                                class="fas fa-pencil-alt"></i></a>&nbsp;

                                        @csrf
                                        @method('DELETE')
                                        <a class="btn-sm btn-danger text-white deleteBtn" href="#"
                                           data-id="{{$consumable->id}}"><i class=" fas fa-trash"></i></a>
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
                <h4>Help with consumables</h4>
                <p>This area can be minimised and will contain a little help on the page that the Consumable is currently
                    on.</p>
            </div>
        </div>

    </section>

@endsection

@section('modals')
    <!-- Delete Modal-->
    <div class="modal fade bd-example-modal-lg" id="removeUserModal" tabindex="-1" role="dialog"
         aria-labelledby="removeUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeUserModalLabel">Are you sure you want to delete this Consumable?
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input id="user-id" type="hidden" value="">
                    <p>Select "Delete" to remove this Consumable from the system.</p>
                    <small class="text-danger">**Warning this is permanent. </small>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" type="button" id="confirmBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="importManufacturerModal" tabindex="-1" role="dialog"
         aria-labelledby="importManufacturerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importManufacturerModalLabel">Importing Data</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="/importconsumables" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>Select "import" to add consumables to the system.</p>
                        <input id="importEmpty" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                               type="file" placeholder="Upload here" name="csv" accept=".csv">

                    </div>

                    <div class="modal-footer">
                        @if(session('import-error'))
                            <div class="alert text-warning ml-0"> {{ session('import-error')}} </div>
                        @endif
                        <a href="https://clpt.sharepoint.com/:x:/s/WebDevelopmentTeam/ERgeo9FOFaRIvmBuTRVcvycBkiTnqHf3aowELiOt8Hoi1Q?e=qKYN6b" target="_blank" class="btn btn-info" >
                            Download Import Template
                        </a>
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>

                        <button type="submit" class="btn btn-success" type="button" id="confirmBtnImport">
                            Import
                        </button>
                    @csrf
                </form>
            </div>
        </div>
    </div>
    <?php session()->flash('import-error', ' Select a file to be uploaded before continuing!');?>
@endsection

@section('js')
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script>
        $('.deleteBtn').click(function () {
            $('#user-id').val($(this).data('id'))
            //showModal
            $('#removeUserModal').modal('show')
        });

        $('#confirmBtn').click(function () {
            var form = '#' + 'form' + $('#user-id').val();
            $(form).submit();
        });

        $(document).ready(function () {
            $('#usersTable').DataTable({
                "columnDefs": [{
                    "targets": [3, 4, 5],
                    "orderable": false,
                }],
                "order": [[1, "asc"]]
            });
        });
        // import

        $('#import').click(function () {
            $('#manufacturer-id-test').val($(this).data('id'))
            //showModal
            $('#importManufacturerModal').modal('show')

        });

        // file input empty
        $("#confirmBtnImport").click(":submit", function (e) {

            if (!$('#importEmpty').val()) {
                e.preventDefault();
            }
        })
    </script>

@endsection
