@extends('layouts.app')
@section('title', 'View Manufacturers')
@section('css')

@endsection

@section('content')
<x-wrappers.nav title="Manufacturers">
    <x-buttons.add :route="route('manufacturers.create')" >Manufacturer(s)</x-buttons.add>
    @can('viewAny', \App\Models\Manufacturer::class)
    <x-buttons.reports :route="route('manufacturer.pdf')">Generate Report</x-buttons.reports>
        @if($manufacturers->count() >1)
        <x-buttons.export route="/exportmanufacturers" />
        @endif
        <x-buttons.import id="import" />
    @endcan
</x-wrappers.nav>
<x-handlers.alerts/>
    <section>
        <p class="mb-4">Below are different tiles, one for each manufacturers stored in the management system. Each tile
            has different manufacturers information that can be created, updated, and deleted.Need Help Click <a href="{{route("documentation.index").'#collapseThirteenManufacturers'}}">here?</a> </p>
      <x-search/>
        <div class="row">
            @foreach($manufacturers as $manufacturer)
                <div class="col-xl-3 col-md-4 mb-4">
                    <div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid {{$manufacturer->photoId}};">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold">{{ $manufacturer->name}}</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                   data-toggle="dropdown"
                                   aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                     aria-labelledby="dropdownMenuLink">
                                     @can('view', $manufacturer)
                                     <a class="dropdown-item" href="{{ route('manufacturers.show', $manufacturer->id)}}">View</a>
                                     @endcan
                                     @can('update', $manufacturer)
                                    <a class="dropdown-item" href="{{route("manufacturers.edit",$manufacturer->id)}}">Edit</a>
                                    @endcan
                                    @can('delete', $manufacturer)
                                    <form id="form{{$manufacturer->id}}"
                                          action="{{route("manufacturers.destroy",$manufacturer->id)}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <a class="dropdown-item deleteBtn" data-id="{{$manufacturer->id}}">Delete</a>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row no-gutters">
                                <div class="col mr-2">
                                    <div class="mb-1">
                                        <p><a href="{{ $manufacturer->supportUrl }}">{{ $manufacturer->supportUrl }}</a>
                                        </p>
                                        <p>Tel: {{ $manufacturer->supportPhone }}</p>
                                        <p>Email: {{ $manufacturer->supportEmail }}</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <img src="{{$manufacturer->photo->path ?? null}}" style="max-width: 50px">
                                </div>
                            </div>
                            <div class="row no-gutters border-top border-info pt-4">
                                <div class="col-12">
                                    <table width="100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center"><span class="display-5 font-weight-bold btn btn-sm rounded text-white bg-lilac px-2" data-toggle="tooltip" data-placement="top" title="Assets"><i class="fas fa-fw fa-tablet-alt"></i></span></th>
                                        <th class="text-center"><span class="display-5 font-weight-bold btn btn-sm rounded text-white bg-lilac px-2" data-toggle="tooltip" data-placement="top" title="Accessories"><i class="fas fa-fw fa-keyboard"></i></span></th>
                                        <th class="text-center"><span class="display-5 font-weight-bold btn btn-sm rounded text-white bg-lilac px-2" data-toggle="tooltip" data-placement="top" title="Components"><i class="fas fa-fw fa-hdd"></i></span></th>
                                        <th class="text-center"><span class="display-5 font-weight-bold btn btn-sm rounded text-white bg-lilac px-2" data-toggle="tooltip" data-placement="top" title="Consumables"><i class="fas fa-fw fa-tint"></i></span></th>
                                        <th class="text-center"><span class="display-5 font-weight-bold btn btn-sm rounded text-white bg-lilac px-2" data-toggle="tooltip" data-placement="top" title="MIscellaneous"><i class="fas fa-fw fa-question"></i></span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">
                                                    @php
                                                        $total = 0;
                                                        foreach($manufacturer->assetModel as $assetModel){
                                                            $total += $assetModel->assets->count();
                                                        }
                                                    @endphp
                                                    {{ $total}}
                                                </td>
                                                <td class="text-center">{{$manufacturer->accessory->count() ?? "N/A"}}</td>
                                                <td class="text-center">{{$manufacturer->component->count() ?? "N/A"}}</td>
                                                <td class="text-center">{{$manufacturer->consumable->count() ?? "N/A"}}</td>
                                                <td class="text-center">{{$manufacturer->miscellanea->count() ?? "N/A"}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <x-paginate :model="$manufacturers"/>
    </section>

@endsection

@section('modals')
    <!-- User Delete Modal-->
    <div class="modal fade bd-example-modal-lg" id="removeManufacturerModal" tabindex="-1" role="dialog"
         aria-labelledby="removeManufacturerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeManufacturerModalLabel">Are you sure you want to delete this
                        Location?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input id="manufacturer-id" type="hidden" value="">
                    <p>Select "Delete" to remove this location from the system.</p>
                    <small class="text-danger">**Warning this is permanent. All assets assigned to this location will
                        become available.</small>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" type="button" id="confirmBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <!-- import-->
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
                <form action="/importmanufacturer" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>Select "import" to add Assets to the system.</p>
                        <input class="form-control"
                               type="file" placeholder="Upload here" name="csv" accept=".csv" id="importEmpty">
                    </div>
                    <div class="modal-footer">
                        @if(session('import-error'))
                            <div class="alert text-warning ml-0"> {{ session('import-error')}} </div>
                        @endif
                            <a href="https://clpt.sharepoint.com/:x:/s/WebDevelopmentTeam/ERE4_YTdj09OgTKDE0rqW5cBA2GpiFOsH-ziakd4zeYYwA?e=Ba63sC" target="_blank" class="btn btn-blue" >
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
<?php session()->flash('import-error', 'Select a file to be uploaded before continuing!');?>
@section('js')
    <script>
        $('.deleteBtn').click(function () {
            $('#manufacturer-id').val($(this).data('id'))
            //showModal
            $('#removeManufacturerModal').modal('show')
        });

        $('#confirmBtn').click(function () {
            var form = '#' + 'form' + $('#manufacturer-id').val();
            $(form).submit();
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
                <?php session()->flash('import-error', ' Please select a file to be uploaded before continuing!');?>
            } else {
                <?php session()->flash('import-error', '');?>            }
        })
    </script>
@endsection
