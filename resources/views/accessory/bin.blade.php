@extends('layouts.app')

@section('title', 'Accessories Recycle Bin')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')


    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Accessories | Recycle Bin</h1>
        <div>
            <a href="{{ route('accessories.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                    class="fas fa-chevron-left fa-sm text-dark-50"></i> Back</a>
            <a href="{{ route('documentation.index')."#collapseSixRecycleBin"}}"
               class="d-none d-sm-inline-block btn btn-sm  bg-yellow shadow-sm"><i
                    class="fas fa-question fa-sm text-dark-50"></i> Recycle Bin Help</a>
            @can('generatePDF', \App\Models\Accessory::class)
                @if ($accessories->count() == 1)
                    <a href="{{ route('accessories.showPdf', $accessories[0]->id)}}"
                       class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm"><i
                            class="fas fa-file-pdf fa-sm text-white-50"></i> Generate Report</a>
                @else
                    <form class="d-inline-block" action="{{ route('accessories.pdf')}}" method="POST">
                        @csrf

                        <input type="hidden" value="{{ json_encode($accessories->pluck('id'))}}" name="accessories"/>
                        <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm"><i
                                class="fas fa-file-pdf fa-sm text-white-50"></i> Generate Report
                        </button>
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
                        @foreach($accessories as $accessory)

                            <tr>
                                <td>{{$accessory->name}}
                                    <br>
                                    <small>{{$accessory->serial_no}}</small>
                                </td>
                                <td class="text-center">
                                    @if($accessory->location->photo())
                                        <img src="{{ asset($accessory->location->photo->path)}}" height="30px"
                                             alt="{{$accessory->location->name}}"
                                             title="{{ $accessory->location->name ?? 'Unnassigned'}}"/>
                                    @else
                                        {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($accessory->location->icon ?? '#666').'">'
                                            .strtoupper(substr($accessory->location->name ?? 'u', 0, 1)).'</span>' !!}
                                    @endif
                                </td>
                                <td class="text-center">{{$accessory->manufacturer->name ?? "N/A"}}</td>
                                <td>{{\Carbon\Carbon::parse($accessory->purchased_date)->format("d/m/Y")}}</td>
                                <td class="text-center">
                                    £{{$accessory->purchased_cost}}
                                    @if($accessory->depreciation)
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
                                <td class="text-center" style="color: {{$accessory->status->colour}};">
                                    <i class="{{$accessory->status->icon}}"></i> {{ $accessory->status->name }}
                                </td>
                                @php $warranty_end = \Carbon\Carbon::parse($accessory->purchased_date)->addMonths($accessory->warranty);@endphp
                                <td class="text-center  d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
                                    {{ $accessory->warranty }} Months

                                    <br><small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }}
                                        Remaining</small></td>
                                <td class="text-right">
                                    <div class="dropdown no-arrow">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                           id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div
                                            class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Accessory Options:</div>
                                            <a href="{{ route('accessories.restore', $accessory->id) }}"
                                               class="dropdown-item">Restore</a>
                                            <form class="d-block" id="form{{$accessory->id}}"
                                                  action="{{ route('accessories.remove', $accessory->id) }}"
                                                  method="POST">
                                                @csrf
                                                @can('delete', $accessory)
                                                    <a class="deleteBtn dropdown-item" href="#"
                                                       data-id="{{$accessory->id}}">Delete</a>
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
                <h4>Help with Accessories</h4>
                <p>This area can be minimised and will contain a little help on the page that the accessory is currently
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
                    <h5 class="modal-title" id="removeUserModalLabel">Are you sure you want to permantley delete this
                                                                      Accessory? </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input id="user-id" type="hidden" value="">
                    <p>Select "Delete" to permantley delete this accessory.</p>
                    <small class="text-danger">**Warning this is permanent and the Accessory will be removed from the
                                               system </small>
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
