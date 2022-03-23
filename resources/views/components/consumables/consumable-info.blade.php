@props(["consumable"])
<div class="col-12 col-lg-8 mb-4">
    <div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid ;">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold" style="">Consumable Information</h6>
        </div>
        <div class="card-body">
            <div class="row no-gutters">
                <div class="col-12"><p class="mb-4 ">Information regarding <strong
                            class="font-weight-bold d-inline-block btn-sm btn-grey shadow-sm p-1"><small>{{ $consumable->name }}</small></strong>
                                                     , along with additional relational data attached.</p>
                    <hr>
                </div>
                <div class="col-12 col-sm-5 col-md-3 p-2">
                    @if($consumable->photo()->exists())
                        <img src="{{ asset($consumable->photo->path) ?? asset('images/svg/consumables-image.svg')}}"
                             width="100%" alt="{{$consumable->name}}">
                    @else
                        <img src="{{asset('images/svg/consumables-image.svg')}}" width="100%"
                             alt="{{$consumable->name}}">
                    @endif
                </div>
                <div class="col-12 col-sm-7 col-md-9 p-2">
                    <table class="table table-sm table-bordered table-striped">
                        <thead>
                        <tr>
                            <th colspan="2">Information</th>
                        </tr>
                        </thead>
                        <tr>
                            <td>Name:</td>
                            <td>{{ $consumable->name }}</td>
                        </tr>
                        <tr>
                            <td>Serial N<span class="">o</span></td>
                            <td>{{ $consumable->serial_no }}</td>
                        </tr>

                    </table>
                    <table class="table table-sm table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>
                                Notes
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $consumable->notes}}</td>
                        </tr>
                        </tbody>
                    </table>

                    <table class="table table-sm table-bordered table-striped">
                        <thead>
                        <tr>
                            <th colspan="3">Status</th>
                        </tr>
                        </thead>
                        <tr>
                            <td>Device Status:</td>
                            <td><strong><i class="{{$consumable->status->icon ?? 'fa fa-circle'}}"
                                           style="color: {{$consumable->status->colour ?? '#666'}};"></i> {{ $consumable->status->name ?? 'No Status Set'}}
                                </strong></td>
                            <td class="text-right">
                                <button class="btn btn-sm btn-blue p-1 font-weight-bold" data-bs-toggle="modal"
                                        data-bs-target="#consumableModalStatus">Change Status
                                </button>
                            </td>
                        </tr>
                    </table>

                    <table class="table table-sm table-bordered table-striped">
                        <thead>
                        <tr>
                            <th colspan="3">Added by:</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $consumable->user->name ?? 'Unkown'}}</td>
                            <td>{{ $consumable->created_at}}</td>
                            <td class="text-right">
                                @if(isset($consumable->user->id))
                                    <a href="{{ route('users.show', $consumable->user->id)}}">
                                        <button class="font-weight-bold btn btn-sm btn-primary p-1">View User</button>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    @if(count($consumable->category) != 0)
                        <table class="table table-sm table-bordered">
                            <tr>
                                <td>
                                    @foreach($consumable->category as $category)
                                        <strong
                                            class="font-weight-bold d-inline-block btn-sm btn-light shadow-sm p-1 m-2"><small>{{ $category->name}}</small></strong>
                                    @endforeach
                                </td>
                            </tr>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>



