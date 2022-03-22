@extends('layouts.app')

@section('title', "View Archive")



@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">View Archive</h1>
        <div>
            <a href="{{ url()->previous() }}" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                    class="fas fa-chevron-left fa-sm text-dark-50"></i> Back</a>
            @can('generatePDF', $archive)
                <a href="{{ route('asset.showPdf', $archive->id)}}"
                   class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm loading"><i
                        class="fas fa-file-pdf fa-sm text-dark-50"></i> Generate Report</a>
            @endcan
            @can('delete', $archive)
                <form class="d-inline-block" id="form{{$archive->id}}"
                      action="{{ route('archives.destroy', $archive->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-coral shadow-sm deleteBtn"
                       data-id="{{$archive->id}}"><i class="fas fa-trash fa-sm text-white-50"></i> Delete</a>
                </form>
            @endcan
        </div>
    </div>

    @if(session('danger_message'))
        <div class="alert alert-danger"> {!! session('danger_message')!!} </div>
    @endif

    @if(session('success_message'))
        <div class="alert alert-success"> {!! session('success_message')!!} </div>
    @endif

    <div class="row row-eq-height">


        <div class="col-12 col-lg-8 mb-4">
            <div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid ;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold" style="">Archive Information</h6>
                </div>
                <div class="card-body">
                    <div class="row no-gutters">
                        <div class="col-12"><p class="mb-4 ">Information regarding an
                                                             'Archived {{ ucfirst($archive->model_type)}}
                                                             , the details regarding its disposal along with the users
                                                             that requested and approved the disposal request.</p>
                            <hr>
                        </div>
                        <div class="col-12">
                            <table class="table table-sm table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th colspan="2">Device Information</th>
                                </tr>
                                </thead>
                                <tr>
                                    <td>Type:</td>
                                    <td><span class="btn btn-sm btn-lilac">{{ ucfirst($archive->model_type) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Name:</td>
                                    <td>{{ $archive->name }}</td>
                                </tr>
                                <tr>
                                    <td>Model N<span class="">o</span></td>
                                    <td>{{$archive->model ?? "N/A"}}</small></td>
                                </tr>
                                <tr>
                                    <td>Serial N<span class="">o</span></td>
                                    <td>{{ $archive->serial_no }}</td>
                                </tr>
                                @if($archive->asset_tag != 0)
                                    <tr>
                                        <td>Asset Tag</span></td>
                                        <td>{{ $archive->asset_tag }}</td>
                                    </tr>
                                @endif
                            </table>
                            @if($archive->created_user()->exists())
                                <table class="table table-sm table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th colspan="3">Created by:</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{ $archive->created_user->name ?? 'Unknown'}}</td>
                                        <td>{{ \Carbon\Carbon::parse($archive->created_on)->format('d/m/Y') }}</td>
                                        <td class="text-right">
                                            @if($archive->created_user()->exists())
                                                <a href="{{ route('users.show', $archive->created_user)}}">
                                                    <button class="font-weight-bold btn btn-sm btn-blue p-1">View User
                                                    </button>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 mb-4">
            <div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid ;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold" style="">Purchase Information</h6>
                </div>
                <div class="card-body">
                    <div class="row no-gutters">
                        <div class="mb-1">
                            <p class="mb-4 ">Information regarding the 'Archived {{ ucfirst($archive->model_type)}}
                                             purchase order, you can find information about the purchase and the
                                             supplier.</p>

                            <table class="table table-sm table-bordered table-striped">
                                <tr>
                                    <td class="col-4">Order N<sup>o</sup>:</td>
                                    <td class="col-8"> {{$archive->order_no ?? 'No Order Information Available'}}</td>
                                </tr>
                                <tr>
                                    <td>Supplier:</td>
                                    <td>
                                        @if($archive->supplier()->exists())
                                            {{ $archive->supplier->name}}<br>
                                            {{ $archive->supplier->address_1 }}<br>
                                            {{ $archive->supplier->address_2 }}<br>
                                            {{ $archive->supplier->city }}<br>
                                            {{ $archive->supplier->county }}<br>
                                            {{ $archive->supplier->postcode }}<br>
                                        @else
                                            <p>No Supplier Information</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Date of Purchase:</td>
                                    <td>
                                        <?php $purchase_date = \Carbon\Carbon::parse($archive->purchased_date);?>
                                        {{ $purchase_date->format('d/m/Y')}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Purchase Cost:</td>
                                    <td>
                                        <p>£{{ $archive->purchased_cost }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Value at Disposal:</td>
                                    <td>
                                        <p>£{{ $archive->archived_cost }}
                                    </td>
                                </tr>
                            </table>
                            @if($archive->supplier()->exists() && $archive->supplier->email != "")
                                <a href="mailto:{{$archive->supplier->email}}">
                                    <button class="btn btn-sm btn-blue"><i class="far fa-envelope"></i> Email Supplier
                                    </button>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row row-eq-height">
        <div class="col-12 col-lg-6 mb-4">
            @if($archive->location()->exists())
                <x-locations.location-modal :asset="$archive"/>
            @endif
        </div>

        <div class="col-12 col-lg-6 mb-4">
            <div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid #666;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold">Disposal Information</h6>
                </div>
                <div class="card-body">
                    <div class="row no-gutters">
                        <div class="col mr-2">
                            <div class="mb-1">
                                <p class="mb-4">Information regarding the 'Archived {{ ucfirst($archive->model_type)}}
                                                disposal, whi requested, who approved it and as to why.</p>

                                <table class="table table-sm table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <td class="text-center">
                                            Archive Information
                                        </td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Requested By:</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ $archive->requested->name ?? 'Unknown' }}</strong><br>
                                            <small>{{ \Carbon\Carbon::parse($archive->created_at)->format('d-m-Y')}}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Approved By:</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ $archive->approved->name ?? 'Unknown' }}</strong><br>
                                            <small>{{ \Carbon\Carbon::parse($archive->updated_at)->format('d-m-Y')}}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Reasons:</td>
                                    </tr>
                                    <tr>
                                        <td>{{ $archive->notes }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                                @if($archive->requested()->exists())
                                    <a href="mailto:{{ $archive->requested->email}}">
                                        <button class="btn btn-sm btn-blue"><i class="far fa-envelope"></i> Email
                                                                                                            Administrator
                                        </button>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>


@endsection

@section('modals')
    <x-modals.delete :archive="true"/>
@endsection

@section('js')
    <script src="{{asset('js/delete.js')}}"></script>


@endsection
