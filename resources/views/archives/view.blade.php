@extends('layouts.app')

@section('title', 'Archives')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>

@endsection

@section('content')
    <x-wrappers.nav :title="$title">
        @can('generatePDF', \App\Models\Archive::class)
                @if($archives->count() != 0)
                    @if ($archives->count() == 1)
                        <a href="{{ route('archives.showPdf', $archives[0]->id)}}"
                           class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                                class="fas fa-file-pdf fa-sm text-dark-50"></i> Generate Report</a>
                    @else
                        <form class="d-inline-block" action="{{ route('archives.pdf')}}" method="POST">
                            @csrf
                            <input type="hidden" value="{{ json_encode($archives->pluck('id'))}}" name="assets"/>
                            <button type="submit"
                                    class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm loading"><i
                                    class="fas fa-file-pdf fa-sm text-dark-50"></i> Generate Report
                            </button>
                        </form>
                    @endif
                @endif
            @endcan
            @if($archives->count() > 1)
                @can('generatePDF', \App\Models\Archive::class)
                    <form class="d-inline-block" action="/exportassets" method="POST">
                        @csrf
                        <input type="hidden" value="{{ json_encode($archives->pluck('id'))}}" name="assets"/>
                        <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-yellow shadow-sm loading">
                            <i
                                class="fas fa-download fa-sm text-dark-50"></i> Export
                        </button>
                    </form>
                @endcan
            @endif

    </x-wrappers.nav>

    @if(session('danger_message'))
        <div class="alert alert-danger"> {!!session('danger_message')!!} </div>
    @endif

    @if(session('success_message'))
        <div class="alert alert-success"> {!! session('success_message')!!} </div>
    @endif

    <section>
        <p class="mb-4">Below are all the Assets stored in the management system. Each has
            different options and locations can created, updated, deleted and filtered</p>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="assetsTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-9 col-md-2"><small>Item</small></th>
                            <th class="d-none d-xl-table-cell"><small>Model</small></th>
                            <th class="col-1 col-md-auto"><small>Location</small></th>
                            <th class="col-1 col-md-auto"><small>Tag</small></th>
                            <th class="d-none d-xl-table-cell"><small>Date</small></th>
                            <th class="d-none d-xl-table-cell"><small>Cost</small></th>
                            <th class="d-none d-xl-table-cell"><small>Supplier</small></th>
                            <th class="col-auto d-none d-xl-table-cell"><small>Requested By</small></th>
                            <th class="col-auto text-center d-none d-md-table-cell"><small>Approved By</small></th>
                            <th class="text-right"><small>Options</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th><small>Item</small></th>
                            <th class="d-none d-xl-table-cell"><small>Model</small></th>
                            <th><small>Location</small></th>
                            <th><small>Tag</small></th>
                            <th class=" d-none d-xl-table-cell"><small>Date</small></th>
                            <th class=" d-none d-xl-table-cell"><small>Cost</small></th>
                            <th class=" d-none d-xl-table-cell"><small>Supplier</small></th>
                            <th class=" d-none d-xl-table-cell"><small>Warranty (M)</small></th>
                            <th class="text-center  d-none d-md-table-cell"><small>Audit Due</small></th>
                            <th class="text-right"><small>Options</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($archives as $archive)
                            <tr>
                                <td>{{$archive->name}}<br><small
                                        class="d-none d-md-inline-block">{{ $archive->serial_no ?? 'N/A'}}</small></td>
                                <td class="text-center d-none d-xl-table-cell">{{ $archive->asset_model ?? 'N/A' }}<br>
                                </td>
                                <td class="text-center text-md-left"
                                    data-sort="{{ $archive->location->name ?? 'Unnassigned'}}">
                                    @if(isset($archive->location->photo->path))
                                        <img src="{{ asset($archive->location->photo->path)}}" height="30px"
                                             alt="{{$archive->location->name}}"
                                             title="{{ $archive->location->name }}<br>{{ $asset->room ?? 'Unknown'}}"/>
                                    @else
                                        {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($archive->location->icon ?? '#666').'" data-toggle="tooltip" data-placement="top" title="">'
                                            .strtoupper(substr($archive->location->name ?? 'u', 0, 1)).'</span>' !!}
                                    @endif
                                    <small class="d-none d-md-inline-block">{{$archive->location->name}}</small>
                                </td>
                                <td>{{ $archive->asset_tag ?? 'N/A'}}</td>
                                <td class="d-none d-md-table-cell" data-sort="{{ strtotime($archive->date)}}">
                                    {{ \Carbon\Carbon::parse($archive->purchased_date)->format('d/m/Y')}}<br>
                                    <small class="text-danger">Disposed
                                        on:{{ \Carbon\Carbon::parse($archive->date)->format('d/m/Y')}}</small>
                                </td>
                                <td class="text-center  d-none d-xl-table-cell">
                                    £{{ $archive->purchased_cost }}<br><small>Value at Disposal -
                                        £{{ $archive->archived_cost}}</small>
                                </td>
                                <td class="text-center d-none d-xl-table-cell">{{$archive->supplier->name ?? "N/A"}}<br><small>Order
                                        No: {{ $archive->order_no ?? 'N/A'}}</small></td>
                                <td class="text-center">
                                    @if($archive->requested()->exists() && $archive->requested->photo()->exists())
                                        <img class="img-profile rounded-circle"
                                             src="{{ asset($archive->requested->photo->path) ?? asset('images/profile.png') }}"
                                             width="50px" title="{{ $archive->requested->name ?? 'Unknown' }}">
                                    @else
                                        <img class="img-profile rounded-circle" src="{{ asset('images/profile.png') }}"
                                             width="50px" title="{{ $archive->requested->name ?? 'Unknown' }}">
                                    @endif
                                    <small>{{ \Carbon\Carbon::parse($archive->created_at)->format("d/m/Y") }}</small>
                                </td>
                                <td class="text-center">
                                    @if($archive->approved()->exists() && $archive->approved->photo()->exists())
                                        <img class="img-profile rounded-circle"
                                             src="{{ asset($archive->approved->photo->path) ?? asset('images/profile.png') }}"
                                             width="50px" title="{{ $archive->approved->name ?? 'Unknown' }}">
                                    @else
                                        <img class="img-profile rounded-circle" src="{{ asset('images/profile.png') }}"
                                             width="50px" title="{{ $archive->approved->name ?? 'Unknown' }}">
                                    @endif
                                    <small>{{ \Carbon\Carbon::parse($archive->updated_at)->format("d/m/Y") }}</small>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown no-arrow">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                           id="dropdownMenu{{$archive->id}}Link"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div
                                            class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenu{{$archive->id}}Link">
                                            <div class="dropdown-header">Archive Options:</div>
                                            <a href="{{ route('archives.restore', $archive->id) }}"
                                                class="dropdown-item">Restore</a>
                                            @can('view', $archive)
                                                <a href="{{ route('archives.show', $archive->id) }}"
                                                   class="dropdown-item">View</a>
                                            @endcan
                                            @can('delete', $archive)
                                                <form id="form{{$archive->id}}"
                                                      action="{{ route('archives.destroy', $archive->id) }}"
                                                      method="POST" class="d-block p-0 m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a class="deleteBtn dropdown-item" href="#"
                                                       data-id="{{$archive->id}}">Delete</a>
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
                <h4>Help with Assets</h4>
                <p>Click <a href="{{route("documentation.index").'#collapseThreeAssets'}}">here</a> for a the
                    Documentation on Assets on Importing ,Exporting , Adding , Removing!</p>
            </div>
        </div>

    </section>
@endsection
<?php session()->flash('import-error', 'Select a file to be uploaded before continuing!');?>

@section('modals')
    <!-- Archive Delete Modal-->
    <div class="modal fade bd-example-modal-lg" id="removeArchiveModal" tabindex="-1" role="dialog"
         aria-labelledby="removeArchiveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeArchiveModalLabel">Are you sure you want to delete this item?
                    </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input id="archive-id" type="hidden" value="">
                    <p>Select "Delete" to remove this item from the system.</p>
                    <small class="text-danger">**Warning this is permanent. All assigned items will be
                        set to Null.</small>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-coral" type="button" id="confirmBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script>
        $('.deleteBtn').click(function () {
            $('#archive-id').val($(this).data('id'))
            //showModal
            $('#removeArchiveModal').modal('show')
        });

        $('#confirmBtn').click(function () {
            var form = '#' + 'form' + $('#archive-id').val();
            $(form).submit();
        });


        $(document).ready(function () {
            $('#assetsTable').DataTable({
                "autoWidth": false,
                "pageLength": 25,
                "columnDefs": [{
                    "targets": [7, 8],
                    "orderable": false
                }],
                "order": [[4, "desc"]],
            });
        });
    </script>
@endsection
