@extends('layouts.app')

@section('title', 'Accessories')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')


    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Accessories</h1>
        <div>
            @can('recycleBin', \App\Models\Accessory::class)
            <a href="{{ route('accessories.bin')}}" class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm"><i
                class="fas fa-trash-alt fa-sm text-white-50"></i> Recycle Bin ({{ \App\Models\Accessory::onlyTrashed()->count()}})</a>
            @endcan
            @can('create', \App\Models\Accessory::class)
            <a href="{{ route('accessories.create')}}" class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm"><i
                    class="fas fa-plus fa-sm text-white-50"></i> Add New Accessory</a>
            @endcan
            @can('generatePDF', \App\Models\Accessory::class)
                @if ($accessories->count() == 1)
                    <a href="{{ route('accessories.showPdf', $accessories[0]->id)}}" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                        class="fas fa-file-pdf fa-sm text-dark-50"></i> Generate Report</button>
                    @else
                    <form class="d-inline-block" action="{{ route('accessories.pdf')}}" method="POST">
                        @csrf
                        <input type="hidden" value="{{ json_encode($accessories->pluck('id'))}}" name="accessories"/>
                    <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm loading"><i
                            class="fas fa-file-pdf fa-sm text-dark-50"></i> Generate Report</button>
                    </form>
                @endif
                @if($accessories->count() >1)
                <a href="/exportaccessories" class="d-none d-sm-inline-block btn btn-sm btn-yellow shadow-sm loading"><i
                    class="fas fa-download fa-sm text-wdarkhite-50"></i>Export</a>
                @endif
            @endcan
            @can('import', \App\Models\Accessory::class)
            <a id="import" class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50 fa-text-width"></i> Import</a>
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
        <p class="mb-4">Below are the different Accessories stored in the management system. Each has
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
                            <th class="text-center"><small>Model</small></th>
                            <th><small>Date</small></th>
                            <th class="text-center"><small>Cost (Value)</small></th>
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
                                <th class="text-center"><small>Model</small></th>
                                <th><small>Purchased Date</small></th>
                                <th class="text-center"><small>Cost (Value)</small></th>
                                <th><small>Supplier</small></th>
                                <th class="text-center"><small>Status</small></th>
                                <th class="text-center"><small>Warranty</small></th>
                                <th class="text-right"><small>Options</small></th>
                            </tr>
                        </tfoot>
                        <tbody>
                        @foreach($accessories as $accessory)

                            <tr>
                                <td>{{$accessory->name}}
                                    <br>
                                    <small>{{$accessory->serial_no}}</small>
                                </td>
                                <td class="text-center">
                                    @if($accessory->location->photo()->exists())
                                        <img src="{{ asset($accessory->location->photo->path)}}" height="30px" alt="{{$accessory->location->name}}" title="{{ $accessory->location->name ?? 'Unnassigned'}}"/>
                                    @else
                                        {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($accessory->location->icon ?? '#666').'">'
                                            .strtoupper(substr($accessory->location->name ?? 'u', 0, 1)).'</span>' !!}
                                    @endif
                                </td>
                                <td class="text-center">{{ $accessory->model ?? 'No Model'}}<br><small>{{$accessory->manufacturer->name ?? "N/A"}}</small></td>
                                <td>{{\Carbon\Carbon::parse($accessory->purchased_date)->format("d/m/Y")}}</td>
                                <td class="text-center">
                                    £{{$accessory->purchased_cost}}
                                    @if($accessory->depreciation()->exists())
                                        <br>
                                        @php
                                            $eol = Carbon\Carbon::parse($accessory->purchased_date)->addYears($accessory->depreciation->years);
                                            if($eol->isPast()){
                                                $dep = 0;
                                            }else{

                                                $age = Carbon\Carbon::now()->floatDiffInYears($accessory->purchased_date);
                                                $percent = 100 / $accessory->depreciation->years;
                                                $percentage = floor($age)*$percent;
                                                $dep = $accessory->purchased_cost * ((100 - $percentage) / 100);
                                            }
                                        @endphp
                                        <small>(*£{{ number_format($dep, 2)}})</small>
                                    @endif
                                </td>
                                <td>{{$accessory->supplier->name ?? 'N/A'}}</td>
                                <td class="text-center"  style="color: {{$accessory->status->colour}};">
                                    <i class="{{$accessory->status->icon}}"></i> {{ $accessory->status->name }}
                                </td>
                                @php $warranty_end = \Carbon\Carbon::parse($accessory->purchased_date)->addMonths($accessory->warranty);@endphp
                                <td class="text-center  d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
                                    {{ $accessory->warranty }} Months<br>
                                    @if(\Carbon\Carbon::parse($warranty_end)->isPast())
                                        <span class="text-coral">{{ 'Expired' }}</span>
                                    @else
                                    <small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }} Remaining</small>
                                    @endif
                                <td class="text-right">
                                    <div class="dropdown no-arrow">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenu{{$accessory->id}}Link"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                             aria-labelledby="dropdownMenu{{$accessory->id}}Link">
                                            <div class="dropdown-header">Accessory Options:</div>
                                            @can('view', $accessory)
                                            <a href="{{ route('accessories.show', $accessory->id) }}" class="dropdown-item">View</a>
                                            @endcan
                                            @can('update', $accessory)
                                                <a href="{{ route('accessories.edit', $accessory->id) }}" class="dropdown-item">Edit</a>
                                            @endcan
                                            @can('transfer', $accessory)
                                                <a  href="#" 
                                                    class="dropdown-item transferBtn" 
                                                    data-model-id="{{$accessory->id}}"
                                                    data-location-from="{{$accessory->location->name ?? 'Unallocated' }}"
                                                    data-location-id="{{ $accessory->location_id }}"
                                                >Transfer</a>
                                            @endcan
                                            @can('dispose', $accessory)
                                                <a  href="#" 
                                                    class="dropdown-item disposeBtn" 
                                                    data-model-id="{{$accessory->id}}"
                                                    data-model-name="{{$accessory->name ?? 'No name'}}"
                                                >Dispose</a>
                                            @endcan
                                            @can('delete', $accessory)
                                                <form id="form{{$accessory->id}}" action="{{ route('accessories.destroy', $accessory->id) }}" method="POST" class="d-block p-0 m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="deleteBtn dropdown-item" href="#"
                                                       data-id="{{$accessory->id}}">Delete</a>
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
                <h4>Help with Accessories</h4>
                <p>Click <a href="{{route("documentation.index").'#collapseEightAccessory'}}">here</a> for a the Documentation on Accessories on Importing ,Exporting , Adding , Removing!</p>
            </div>
        </div>

    </section>

@endsection

@section('modals')
    <!-- Disposal Modal-->
    <div class="modal fade bd-example-modal-lg" id="requestDisposal" tabindex="-1" role="dialog" aria-labelledby="requestDisposalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('request.disposal')}}" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="requestDisposalLabel">Request to Dispose of the Accessory?
                        </h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            @csrf
                            <input name="model_type" type="hidden" value="accessory">
                            <input id="dispose_id" name="model_id" type="hidden" value="">
                            <input type="text" value="" id="accessory" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label for="disposal_date">Date of Disposal</label>
                            <input type="date" value="" id="disposed_date" name="disposed_date" class="form-control" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}">
                        </div>
                        <div class="form-group">
                            <label for="notes">Reasons for:</label>
                            <textarea name="notes" class="form-control" rows="5"></textarea>
                        </div>
                        <small>This will send a request to the administrator. The administrator will then decide to approve or reject the request. You will be notified via email.</small>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-coral" type="submit">Request Disposal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Transfer Modal-->
    <div class="modal fade bd-example-modal-lg" id="requestTransfer" tabindex="-1" role="dialog" aria-labelledby="requestTransferLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('request.transfer')}}" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="requestTransferLabel">Request to Transfer this Accessory to another Location?
                        </h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            @csrf
                            <input name="model_type" type="hidden" value="accessory">
                            <input id="model_id" name="model_id" type="hidden" value="">
                            <input id="location_id" name="location_from" type="hidden" value="">
                            <input id="location_from" type="text" class="form-control" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="disposal_date">Date of Transfer</label>
                            <input type="date" value="" id="transfer_date" name="transfer_date" class="form-control" value="">
                        </div>
                        <div class="form-group">
                            <label for="School Location">Transfer to:</label><span
                                class="text-danger">*</span>
                            <select type="text"
                                class="form-control mb-3 @if($errors->has('location_id')){{'border-danger'}}@endif"
                                name="location_to" required>
                                <option value="0" selected>Please select a Location</option>
                                @foreach($locations as $location)
                                @php if(old('location_id')){ $id=old('location_id');}else{ $id= $accessory->location_id;} @endphp
                                <option value="{{$location->id}}" @if($id == $location->id){{ 'selected'}}@endif>{{$location->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="notes">Additional Comments:</label>
                            <textarea name="notes" class="form-control" rows="5"></textarea>
                        </div>
                        <small>This will send a request to the administrator. The administrator will then decide to approve or reject the request. You will be notified via email.</small>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-lilac" type="submit">Request Transfer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete Modal-->
    <div class="modal fade bd-example-modal-lg" id="removeUserModal" tabindex="-1" role="dialog"
         aria-labelledby="removeUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeUserModalLabel">Are you sure you want to send this Accessory to the Recycle Bin?
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input id="user-id" type="hidden" value="">
                    <p>Select "Send to Bin" to send this accessory to the Recycle Bin.</p>
                    <small class="text-danger">**Warning this is not permanent and the Accessory can be restored from the Recycle Bin. </small>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" type="button" id="confirmBtn">Send to Bin</button>
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
                <form action="/importacessories" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>Select "import" to add Accessories to the system.</p>
                        <input id="importEmpty" class="form-control"
                               type="file" placeholder="Upload here" name="csv" accept=".csv">

                    </div>

                    <div class="modal-footer">
                        @if(session('import-error'))
                            <div class="alert text-warning ml-0"> {{ session('import-error')}} </div>
                        @endif
                        <a href="https://clpt.sharepoint.com/:x:/s/WebDevelopmentTeam/EUS0PE9tn-xFsPAqFeza6OQB9Cm8EONyQNd4eTdkmXJnXw?e=wCJU5b" target="_blank" class="btn btn-blue" >
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

        $('.transferBtn').click(function(){
            $('#model_id').val($(this).data('model-id'));
            $('#location_id').val($(this).data('location-id'));
            $('#location_from').val($(this).data('location-from'));
            $('#requestTransfer').modal('show');
        });

        $('.disposeBtn').click(function(){
            $('#accessory_name').val($(this).data('model-name'));
            $('#dispose_id').val($(this).data('model-id'));
            $('#requestDisposal').modal('show');
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
        });

    </script>

@endsection
