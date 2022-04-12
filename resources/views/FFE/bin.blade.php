@extends('layouts.app')

@section('title', 'FFE Recycle Bin')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">FFE | Recycle Bin</h1>
        <div>

            <a href="{{ route('ffes.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                    class="fas fa-chevron-left fa-sm text-dark-50"></i> Back</a>
            @can('recycleBin' ,\App\Models\FFE::class)
                <a href="{{ route('documentation.index')."#collapseSixRecycleBin"}}"
                   class="d-none d-sm-inline-block btn btn-sm  bg-yellow shadow-sm"><i
                        class="fas fa-question fa-sm text-dark-50"></i> Recycle Bin Help</a>
            @endcan

            @can('generatePDF', \App\Models\FFE::class)
                @if ($ffes->count() == 1)
                    <a href="{{ route('ffes.showPdf', $ffes[0]->id)}}"
                       class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm"><i
                            class="fas fa-file-pdf fa-sm text-white-50"></i> Generate Report</a>
                @else
                    <form class="d-inline-block" action="{{ route('ffes.pdf')}}" method="POST">
                        @csrf

                        <input type="hidden" value="{{ json_encode($ffes->pluck('id'))}}" name="ffes"/>
                        <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm"><i
                                class="fas fa-file-pdf fa-sm text-white-50"></i> Generate Report
                        </button>
                    </form>
                @endif
            @endcan
        </div>
    </div>

    <x-handlers.alerts />

    <section>
        <p class="mb-4">Below are the different Accessories stored in the management system. Each has
                        different options and locations can be created, updated, and deleted.</p>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
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
                        @foreach($ffes as $ffe)

                            <tr>
                                <td>{{$ffe->name}}
                                    <br>
                                    <small>{{$ffe->serial_no}}</small>
                                </td>
                                <td class="text-center">
                                    @if($ffe->location->photo())
                                        <img src="{{ asset($ffe->location->photo->path)}}" height="30px"
                                             alt="{{$ffe->location->name}}"
                                             title="{{ $ffe->location->name ?? 'Unnassigned'}}"/>
                                    @else
                                        {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($ffe->location->icon ?? '#666').'">'
                                            .strtoupper(substr($ffe->location->name ?? 'u', 0, 1)).'</span>' !!}
                                    @endif
                                </td>
                                <td class="text-center">{{$ffe->manufacturer->name ?? "N/A"}}</td>
                                <td>{{\Carbon\Carbon::parse($ffe->purchased_date)->format("d/m/Y")}}</td>
                                <td class="text-center">
                                    £{{$ffe->purchased_cost}}
                                    @if($ffe->depreciation)
                                        <br>
                                        @php
                                            $eol = Carbon\Carbon::parse($ffe->purchased_date)->addYears($ffe->depreciation);
                                            if($eol->isPast()){
                                                $dep = 0;
                                            }else{

                                                $age = Carbon\Carbon::now()->floatDiffInYears($ffe->purchased_date);
                                                $percent = 100 / $ffe->depreciation;
                                                $percentage = floor($age)*$percent;
                                                $dep = $ffe->purchased_cost * ((100 - $percentage) / 100);
                                            }
                                        @endphp
                                        <small>(*£{{ number_format($dep, 2)}})</small>
                                    @endif
                                </td>
                                <td>{{$ffe->supplier->name ?? 'N/A'}}</td>
                                <td class="text-center" style="color: {{$ffe->status->colour}};">
                                    <i class="{{$ffe->status->icon}}"></i> {{ $ffe->status->name }}
                                </td>
                                @php $warranty_end = \Carbon\Carbon::parse($ffe->purchased_date)->addMonths($ffe->warranty);@endphp
                                <td class="text-center  d-none d-xl-table-cell" data-sort="{{ $warranty_end }}">
                                    {{ $ffe->warranty }} Months

                                    <br><small>{{ round(\Carbon\Carbon::now()->floatDiffInMonths($warranty_end)) }}
                                        Remaining</small></td>
                                <td class="text-right">
                                    <div class="dropdown no-arrow">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                           id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div
                                            class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">FFE Options:</div>
                                            <a href="{{ route('ffe.restore', $ffe->id) }}"
                                               class="dropdown-item">Restore</a>
                                            <form class="d-block" id="form{{$ffe->id}}"
                                                  action="{{ route('ffe.remove', $ffe->id) }}"
                                                  method="POST">
                                                @csrf
                                                @can('delete', $ffe)
                                                    <a class="deleteBtn dropdown-item" href="#"
                                                       data-id="{{$ffe->id}}">Delete</a>
                                                @endcan
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @if($ffes->count() == 0)
                            <tr>
                                <td colspan="9" class="text-center">No results returned</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
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
    <x-modals.delete :archive="true">Accessory</x-modals.delete>
@endsection

@section('js')
    <script src="{{asset('js/delete.js')}}" defer></script>

@endsection
