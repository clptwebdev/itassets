@props(["accessory"])
<div class="col-12 col-lg-8 mb-4">
    <div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid ;">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold" style="">Accessory Information</h6>
        </div>
        <div class="card-body">
            <div class="row no-gutters">
                <div class="col-12"><p class="mb-4 ">Information regarding <strong
                            class="font-weight-bold d-inline-block btn-sm btn-grey shadow-sm p-1"><small>{{ $accessory->name }}</small></strong>
                                                     , along with additional relational data attached.</p>
                    <hr>
                </div>
                <div class="col-12 col-sm-5 col-md-3 p-2">
                    @if($accessory->photo()->exists())
                        <img src="{{ asset($accessory->photo->path) ?? asset('images/svg/device-image.svg')}}"
                             width="100%" alt="{{$accessory->name}}">
                    @else
                        <img src="{{asset('images/svg/device-image.svg')}}" width="100%" alt="{{$accessory->name}}">
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
                            <td>{{ $accessory->name }}</td>
                        </tr>
                        <tr>
                            <td>Model:</td>
                            <td>{{ $accessory->model ?? 'No Model' }}</td>
                        </tr>
                        <tr>
                            <td>Serial N<span class="">o</span></td>
                            <td>{{ $accessory->serial_no }}</td>
                        </tr>
                    </table>

                    <table class="table table-sm table-bordered table-striped">
                        <thead>
                        <tr>
                            <th colspan="3">Status</th>
                        </tr>
                        </thead>
                        <tr>
                            <td>Device Status:</td>
                            <td><strong><i class="{{$accessory->status->icon ?? 'fa fa-circle'}}"
                                           style="color: {{$accessory->status->colour ?? '#666'}};"></i> {{ $accessory->status->name ?? 'No Status Set'}}
                                </strong></td>
                            <td class="text-right">
                                <button class="btn btn-sm btn-blue p-1 font-weight-bold" data-bs-toggle="modal"
                                        data-bs-target="#accessoryModalStatus">Change Status
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
                            <td>{{ $accessory->user->name ?? 'Unkown'}}</td>
                            <td>{{ $accessory->created_at}}</td>
                            <td class="text-right">
                                @if(isset($accessory->user->id))
                                    <a href="{{ route('users.show', $accessory->user->id)}}">
                                        <button class="font-weight-bold btn btn-sm btn-primary p-1">View User</button>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    @if(count($accessory->category) != 0)
                        <table class="table table-sm table-bordered">
                            <tr>
                                <td>
                                    @foreach($accessory->category as $category)
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



