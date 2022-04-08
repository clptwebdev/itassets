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
                    <x-buttons.reports :route="route('archives.showPdf', $archives[0]->id)"/>
                @else
                    <x-form.layout class="d-inline-block" :action="route('archives.pdf')">
                        <x-form.input type="hidden" name="assets" :label="false" formAttributes="required"
                                      :value="json_encode($archives->pluck('id'))"/>
                        <x-buttons.submit class="btn-blue">Generate Report</x-buttons.submit>
                    </x-form.layout>
                @endif
            @endif
            @if($archives->count() > 1)
                <x-form.layout class="d-inline-block" action="/exportassets">
                    <x-form.input type="hidden" name="assets" :label="false" formAttributes="required"
                                  :value="json_encode($archives->pluck('id'))"/>
                    <x-buttons.submit class="btn-yellow">export</x-buttons.submit>
                </x-form.layout>
            @endcan
        @endcan
    </x-wrappers.nav>
    <x-handlers.alerts/>
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
                                        {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($archive->location->icon ?? '#666').'" data-bs-toggle="tooltip" data-bs-placement="top" title="">'
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
                                                                                                                               No: {{ $archive->order_no ?? 'N/A'}}</small>
                                </td>
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
                                    <x-wrappers.table-settings>
                                        <a href="{{ route('archives.restore', $archive->id) }}" class="dropdown-item">Restore</a>
                                        <x-buttons.dropdown-item :route="route('archives.show', $archive->id)">
                                            View
                                        </x-buttons.dropdown-item>
                                        <x-form.layout method="DELETE" class="d-block p-0 m-0" :id="'form'.$archive->id"
                                                       :action="route('archives.destroy', $archive->id)">
                                            <x-buttons.dropdown-item class="deleteBtn" :data="$archive->id">
                                                Delete
                                            </x-buttons.dropdown-item>
                                        </x-form.layout>
                                    </x-wrappers.table-settings>
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
    <x-modals.delete :archive="true"/>
@endsection

@section('js')

    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('js/delete.js')}}"></script>
@endsection
