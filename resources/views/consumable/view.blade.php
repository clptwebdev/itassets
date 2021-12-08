@extends('layouts.app')

@section('title', 'View Consumables')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')


    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Consumables</h1>
        <div>
            @can('recycleBin', \App\Models\Consumable::class)
            <a href="{{ route('consumables.bin')}}" class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm">
                <i class="fas fa-trash-alt fa-sm text-white-50"></i> Recycle Bin ({{ \App\Models\Consumable::onlyTrashed()->count()}})</a>
            @endcan
            @can('create', \App\Models\Consumable::class)
            <a href="{{ route('consumables.create')}}" class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Add New Consumable</a>
            @endcan
            @can('generatePDF', \App\Models\Consumable::class)
                @if ($consumables->count() == 1)
                    <a href="{{ route('consumables.showPdf', $consumables[0]->id)}}" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm loading"><i
                        class="fas fa-file-pdf fa-sm text-white-50"></i> Generate Report</button>
                    @else
                    <form class="d-inline-block" action="{{ route('consumables.pdf')}}" method="POST">
                        @csrf
                        <input type="hidden" value="{{ json_encode($consumables->pluck('id'))}}" name="consumables"/>
                    <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm loading"><i
                            class="fas fa-file-pdf fa-sm text-white-50"></i> Generate Report</button>
                    </form>
                @endif
                @if($consumables->count() >1)
                <a href="/exportconsumables" class="d-none d-sm-inline-block btn btn-sm btn-yellow shadow-sm loading"><i
                    class="fas fa-download fa-sm text-white-50"></i>Export</a>
                @endif
            @endcan
            @can('import', \App\Models\Consumable::class)
            <a id="import" class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm">
                <i class="fas fa-download fa-sm text-white-50 fa-text-width"></i> Import</a>
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
        <p class="mb-4">Below are the different Consumables stored in the management system. Each has
            different options and locations can created, updated, and deleted.</p>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="usersTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th><small>Name</small></th>
                            <th class="text-center"><small>Location</small></th>
                            <th class="text-center"><small>Manufacturers</small></th>
                            <th><small>Purchased Date</small></th>
                            <th><small>Purchased Cost</small></th>
                            <th><small>Supplier</small></th>
                            <th class="text-center"><small>Status</small></th>
                            <th class="text-center"><small>Warranty</small></th>
                            <th class="text-right"><small>Options</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th><small>Name</small></th>
                                <th class="text-center"><small>Location</small></th>
                                <th class="text-center"><small>Manufacturers</small></th>
                                <th><small>Purchased Date</small></th>
                                <th><small>Purchased Cost</small></th>
                                <th><small>Supplier</small></th>
                                <th class="text-center"><small>Status</small></th>
                                <th class="text-center"><small>Warranty</small></th>
                                <th class="text-right"><small>Options</small></th>
                            </tr>
                        </tfoot>
                        <tbody>
                        @foreach($consumables as $consumable)
                            <tr>
                                <td>{{$consumable->name}}
                                    <br>
                                    <small>{{$consumable->serial_no}}</small>
                                </td>
                                <td class="text-center">
                                    @if($consumable->location()->exists())
                                    @if($consumable->location->photo()->exists())
                                        <img src="{{ asset($consumable->location->photo->path)}}" height="30px" alt="{{$consumable->location->name}}" title="{{ $consumable->location->name ?? 'Unnassigned'}}"/>'
                                    @else
                                        {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($consumable->location->icon ?? '#666').'">'
                                            .strtoupper(substr($consumable->location->name ?? 'u', 0, 1)).'</span>' !!}
                                    @endif
                                        @endif
                                </td>
                                <td class="text-center">{{$consumable->manufacturer->name ?? "N/A"}}</td>
                                <td data-sort="{{strtotime($consumable->purchased_date)}}">{{\Carbon\Carbon::parse($consumable->purchased_date)->format("d/m/Y")}}</td>
                                <td>£{{$consumable->purchased_cost}}</td>
                                <td>{{$consumable->supplier->name ?? 'N/A'}}</td>
                                <td class="text-center">{{$consumable->status->name ??'N/A'}}</td>
                                @php $warranty_end = \Carbon\Carbon::parse($consumable->purchased_date)->addMonths($consumable->warranty);@endphp
                                <td class="text-center  d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
                                    {{ $consumable->warranty }} Months

                                    <br><small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</small>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown no-arrow">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                             aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Consumable Options:</div>
                                            @can('view', $consumable)
                                            <a href="{{ route('consumables.show', $consumable->id) }}" class="dropdown-item">View</a>
                                            @endcan
                                            @can('update', $consumable)
                                                <a href="{{ route('consumables.edit', $consumable->id) }}" class="dropdown-item">Edit</a>
                                            @endcan
                                            @can('delete', $consumable)
                                                <form id="form{{$consumable->id}}" action="{{ route('consumables.destroy', $consumable->id) }}" method="POST" class="d-block p-0 m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="deleteBtn dropdown-item" href="#"
                                                       data-id="{{$consumable->id}}">Delete</a>
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
                <h4>Help with Consumable</h4>
                <p>Click <a href="{{route("documentation.index").'#collapseTwentyConsumables'}}">here</a> for the Documentation on Consumables on Importing ,Exporting , Adding , Removing!</p>

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
                    <h5 class="modal-title" id="removeUserModalLabel">Are you sure you want to send this Consumable to the Recycle Bin?
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input id="user-id" type="hidden" value="">
                    <p>Select "Send to Bin" to send this Consumable to the Recycle Bin.</p>
                    <small class="text-coral">**Warning this is not permanent. The consumable can be restored in the Recycle Bin </small>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-coral" type="button" id="confirmBtn">Send to Bin</button>
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
                        <input id="importEmpty" class="form-control"
                               type="file" placeholder="Upload here" name="csv" accept=".csv">

                    </div>

                    <div class="modal-footer">
                        @if(session('import-error'))
                            <div class="alert text-warning ml-0"> {{ session('import-error') ?? ' Select a file to be uploaded before continuing!'}} </div>
                        @endif
                        <a href="https://clpt.sharepoint.com/:x:/s/WebDevelopmentTeam/EWmxI4Q89eVMjPBSGed7QVQBa6Vk_gZFq_xPip9bTUx5rQ?e=8FQRHP" target="_blank" class="btn btn-blue" >
                            Download Import Template
                        </a>
                        <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>

                        <button type="submit" class="btn btn-green" type="button" id="confirmBtnImport">
                            Import
                        </button>
                    @csrf
                </form>
            </div>
        </div>
    </div>
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
                    "targets": [8],
                    "orderable": false,
                }],
                "order": [[3, "desc"]]
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
