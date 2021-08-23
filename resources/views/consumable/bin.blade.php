@extends('layouts.app')

@section('title', 'Consumables Recycle Bin')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')


    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Consumables | Recycle Bin</h1>
        <div>
            <a href="{{ route('consumables.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                class="fas fa-chevron-left fa-sm text-white-50"></i> Back</a>
            @can('generatePDF', \App\Models\Consumable::class)
                @if ($consumables->count() == 1)
                    <a href="{{ route('consumables.showPdf', $consumables[0]->id)}}" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm"><i
                        class="fas fa-file-pdf fa-sm text-white-50"></i> Generate Report</a>
                    @else
                    <form class="d-inline-block" action="{{ route('consumables.pdf')}}" method="POST">
                        @csrf
                        <input type="hidden" value="{{ json_encode($consumables->pluck('id'))}}" name="consumables"/>
                    <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm"><i
                            class="fas fa-file-pdf fa-sm text-white-50"></i> Generate Report</button>
                    </form>                
                @endif
            @endcan
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
                            <th><small>Name</small></th>
                            <th class="text-center"><small>Location</small></th>
                            <th class="text-center"><small>Manufacturers</small></th>
                            <th><small>Date</small></th>
                            <th><small>Cost</small></th>
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
                                    @if($consumable->location->photo()->exists())
                                        <img src="{{ asset($consumable->location->photo->path)}}" height="30px" alt="{{$consumable->location->name}}" title="{{ $consumable->location->name ?? 'Unnassigned'}}"/>
                                    @else
                                        {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($consumable->location->icon ?? '#666').'">'
                                            .strtoupper(substr($consumable->location->name ?? 'u', 0, 1)).'</span>' !!}
                                    @endif  
                                </td>
                                <td class="text-center">{{$consumable->manufacturer->name ?? "N/A"}}</td>
                                <td>{{\Carbon\Carbon::parse($consumable->purchased_date)->format("d/m/Y")}}</td>
                                <td>£{{$consumable->purchased_cost}}</td>
                                <td>{{$consumable->supplier->name ?? 'N/A'}}</td>
                                <td class="text-center"  style="color: {{$consumable->status->colour ?? '#666'}};">
                                    <i class="{{$consumable->status->icon ?? 'fas fa-circle'}}"></i> {{ $consumable->status->name ?? 'N/A' }}
                                </td>
                                @php $warranty_end = \Carbon\Carbon::parse($consumable->purchased_date)->addMonths($consumable->warranty);@endphp
                                <td class="text-center  d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
                                    {{ $consumable->warranty }} Months

                                    <br><small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</small></td>
                                <td class="text-right">
                                    <div class="dropdown no-arrow">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Consumable Options:</div>
                                            <a href="{{ route('consumables.restore', $consumable->id) }}"
                                                class="dropdown-item">Restore</a>
                                            <form class="d-block" id="form{{$consumable->id}}" action="{{ route('consumables.remove', $consumable->id) }}" method="POST">   
                                                @csrf
                                                @can('delete', $consumable)
                                                <a class="deleteBtn dropdown-item" href="#"
                                                    data-id="{{$consumable->id}}">Delete</a>
                                                @endcan
                                            </form>
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
                <h4>Help with Consumables</h4>
                <p>This area can be minimised and will contain a little help on the page that the consumable is currently
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
                    <h5 class="modal-title" id="removeUserModalLabel">Are you sure you want to permantley delete this Consumable?
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input id="user-id" type="hidden" value="">
                    <p>Select "Delete" to permantley delete this consumable.</p>
                    <small class="text-danger">**Warning this is permanent and the consumable will be removed from the system </small>
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

    </script>

@endsection
