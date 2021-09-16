@props(["miscellaneou"])
<div class="col-12 col-lg-8 mb-4">
    <div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid ;">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold" style="">Miscellanea Information</h6>
        </div>
        <div class="card-body">
            <div class="row no-gutters">
                <div class="col-12"><p class="mb-4 ">Information regarding <strong
                            class="font-weight-bold d-inline-block btn-sm btn-grey shadow-sm p-1"><small>{{ $miscellaneou->name }}</small></strong>
                        , along with additional relational data attached.</p>
                    <hr>
                </div>
                <div class="col-12 col-sm-5 col-md-3 p-2">
                    @if($miscellaneou->photo()->exists())
                        <img src="{{ asset($miscellaneou->photo->path) ?? asset('images/svg/misc-image.svg')}}" width="100%" alt="{{$miscellaneou->name}}">
                    @else
                        <img src="{{asset('images/svg/misc-image.svg')}}" width="100%" alt="{{$miscellaneou->name}}">
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
                            <td>{{ $miscellaneou->name }}</td>
                        </tr>
                        <tr>
                            <td>Serial N<span class="">o</span></td>
                            <td>{{ $miscellaneou->serial_no }}</td>
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
                            <td>{{ $miscellaneou->notes}}</td>
                        </tr>
                        </tbody>
                    </table>

                    <table class="table table-sm table-bordered table-striped">
                        <thead>
                        <tr>
                            <th colspan="3">Status </th>
                        </tr>
                        </thead>
                        <tr>
                            <td>Device Status: </td>
                            <td><strong>{{ $miscellaneou->status->name ?? 'No Status Set'}}</strong></td>
                            <td class="text-right"><button class="btn btn-sm btn-blue p-1 font-weight-bold" data-toggle="modal" data-target="#changeStatus">Change Status</button></td>
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
                            <td>{{ $miscellaneou->user->name ?? 'Unkown'}}</td>
                            <td>{{ $miscellaneou->created_at}}</td>
                            <td class="text-right">
                                @if(isset($miscellaneou->user->id))
                                    <a href="{{ route('users.show', $miscellaneou->user->id)}}">
                                        <button class="font-weight-bold btn btn-sm btn-blue p-1">View User</button></a>
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    @if(count($miscellaneou->category) != 0)
                        <table class="table table-sm table-bordered">
                            <tr>
                                <td>
                                    @foreach($miscellaneou->category as $category)
                                        <strong class="font-weight-bold d-inline-block btn-sm btn-lilac shadow-sm p-1 m-2"><small>{{ $category->name}}</small></strong>
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



