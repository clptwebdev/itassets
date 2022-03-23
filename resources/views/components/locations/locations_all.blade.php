<div class="row row-eq-height mb-4">
    @foreach($locations as $location)
        <div class="col-md-12 col-xl-3 mb-3">
            <div class="card shadow bg-white" style="border-left: solid 5px {{$location->icon ?? '#666'}};">
                <div class="card-body">
                    <div class="row pb-2">
                        <div class="col-12 col-md-10">
                            <span style="color:{{$location->icon}};">{{ $location->name}}</span>
                            <div class="text-gray-50 small">{{$location->address_1}}
                                , @if($location->address_2 != ""){{ $location->address_2}},@endif {{ $location->city}}
                                , {{ $location->postcode}} </div>
                        </div>
                        <div class="col-12 col-md-2" background>
                            <div class="border border-dark bg-white"
                                 style="height: 50px; width: 50px; border-radius: 50%; overflow: hidden; margin: auto;">
                                @if(isset($location->photo->path))
                                    <img src="{{ asset($location->photo->path)}}"
                                         style="width: 100%; height: 100%; object-fit:cover; " alt="{{$location->name}}"
                                         title="{{ $location->name ?? 'Unnassigned'}}"/>
                                @else
                                    {!! '<span class="d-flex justify-content-center align-items-center font-weight-bold bg-white" style="color:'.strtoupper($location->icon ?? '#666').'; height: 100%; width: 100%; font-size: 2.5rem">'
                                        .strtoupper(substr($location->name ?? 'u', 0, 1)).'</span>' !!}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="border-top border-light pt-4"></div>
                    <div class="row no-gutters border-top border-light mt-4 pt-4">
                        <div class="col-12">
                            <table width="100%">
                                <thead>
                                <tr>
                                    <th class="text-center"><span
                                            class="display-5 font-weight-bold btn btn-sm rounded text-white bg-lilac px-2"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Assets"><i
                                                class="fas fa-fw fa-tablet-alt"></i></span></th>
                                    <th class="text-center"><span
                                            class="display-5 font-weight-bold btn btn-sm rounded text-white bg-lilac px-2"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Accessories"><i
                                                class="fas fa-fw fa-keyboard"></i></span></th>
                                    <th class="text-center"><span
                                            class="display-5 font-weight-bold btn btn-sm rounded text-white bg-lilac px-2"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Components"><i
                                                class="fas fa-fw fa-hdd"></i></span></th>
                                    <th class="text-center"><span
                                            class="display-5 font-weight-bold btn btn-sm rounded text-white bg-lilac px-2"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Consumables"><i
                                                class="fas fa-fw fa-tint"></i></span></th>
                                    <th class="text-center"><span
                                            class="display-5 font-weight-bold btn btn-sm rounded text-white bg-lilac px-2"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="MIscellaneous"><i
                                                class="fas fa-fw fa-question"></i></span></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-center">{{$location->asset->count() ?? "N/A"}}</td>
                                    <td class="text-center">{{$location->accessory->count() ?? "N/A"}}</td>
                                    <td class="text-center">{{$location->components->count() ?? "N/A"}}</td>
                                    <td class="text-center">{{$location->consumable->count() ?? "N/A"}}</td>
                                    <td class="text-center">{{$location->miscellanea->count() ?? "N/A"}}</td>
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
