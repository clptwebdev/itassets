@props(["component"])
<div class="col-12 col-lg-8 mb-4">
    <div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid ;">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold" style="">Component Information</h6>
        </div>
        <div class="card-body">
            <div class="row no-gutters">
                <div class="col-12"><p class="mb-4 ">Information regarding <strong
                        class="font-weight-bold d-inline-block btn-sm btn-secondary shadow-sm p-1"><small>{{ $component->name }}</small></strong>
                    , along with additional relational data attached.</p>
                <hr>
                </div>
                <div class="col-12 col-sm-5 col-md-3 p-2">
                    @if($component->photo()->exists())
                    <img src="{{ asset($component->photo->path) ?? asset('images/svg/device-image.svg')}}" width="100%" alt="{{$component->name}}">
                    @else
                    <img src="{{asset('images/svg/device-image.svg')}}" width="100%" alt="{{$component->name}}">
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
                            <td>{{ $component->name }}</td>
                        </tr>
                        <tr>
                            <td>Serial N<span class="">o</span></td>
                            <td>{{ $component->serial_no }}</td>
                        </tr>
                    </table>

                    <table class="table table-sm table-bordered table-striped">
                        <thead>
                        <tr>
                            <th colspan="3">Status </th>
                        </tr>
                        </thead>
                        <tr>
                            <td>Device Status: </td>
                            <td><strong><i class="{{$component->status->icon ?? 'fa fa-circle'}}" style="color: {{$component->status->colour ?? '#666'}};"></i> {{ $component->status->name ?? 'No Status Set'}}</strong></td>
                            <td class="text-right"><button class="btn btn-sm btn-secondary p-1 font-weight-bold" data-toggle="modal" data-target="#componentModalStatus">Change Status</button></td>
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
                                <td>{{ $component->user->name ?? 'Unkown'}}</td>
                                <td>{{ $component->created_at}}</td>
                                <td class="text-right">
                                    @if(isset($component->user->id))
                                    <a href="{{ route('users.show', $component->user->id)}}">
                                    <button class="font-weight-bold btn btn-sm btn-primary p-1">View User</button></a>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @if(count($component->category) != 0)
                    <table class="table table-sm table-bordered">
                        <tr>
                            <td>
                                @foreach($component->category as $category)
                                <strong class="font-weight-bold d-inline-block btn-sm btn-light shadow-sm p-1 m-2"><small>{{ $category->name}}</small></strong>
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



