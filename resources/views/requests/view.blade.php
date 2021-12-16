@extends('layouts.app')

@section('title', 'User Requests')

@section('css')
@endsection

@section('content')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">User Requests</h1>
    <div>
    </div>
</div>

@if(session('danger_message'))
<div class="alert alert-danger"> {!! session('danger_message')!!} </div>
@endif

@if(session('success_message'))
<div class="alert alert-success"> {!! session('success_message')!!} </div>
@endif

<section>
    <p class="mb-4">Below are the requests made by the Users of the system. These can include requesting access, asset transfer or asset disposal.</p>
    <!-- DataTales Example -->

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive" id="table">
                <table id="assetsTable" class="table table-striped">
                    <thead>
                    <tr>
                        <th><small>Type</small></th>
                        <th><small>Model</small></th>
                        <th><small>Requested By</small></th>
                        <th><small>Notes</small></th>
                        <th><small>Date</small></th>
                        <th><small>Status</small></th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th><small>Type</small></th>
                        <th><small>Model</small></th>
                        <th><small>Requested By</small></th>
                        <th><small>Notes</small></th>
                        <th><small>Date</small></th>
                        <th><small>Status</small></th>
                    </tr>
                    </tfoot>
                    <tbody>
                        @foreach($requests as $request)
                        <tr>
                            <td>
                                @if($request->type == 'disposal')
                                <i class="fas fa-trash text-coral"></i>
                                @else
                                <i class="fas fa-exchange-alt text-green"></i>
                                @endif
                            </td>
                            <td>{{ 'Model Name' }}</td>
                            <td>
                                @php($user = App\Models\User::find($request->user_id))
                                {{ $user->name ?? 'Unknown'}}
                            </td>
                            <td>{{ $request->notes }}</td>
                            <td>{{ \Carbon\Carbon::parse($request->date)->format('d-m-Y') }}</td>
                            <td>{{ $request->status }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- <div class="d-flex justify-content-between align-content-center">
                    <div>
                        @if($assets->hasPages())
                        {{ $assets->links()}}
                        @endif
                    </div>
                    <div class="text-right">
                        Showing Assets {{ $assets->firstItem() }} to {{ $assets->lastItem() }} ({{ $assets->total() }} Total Results)
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    {{-- @foreach($requests as $request)
    <div class="card shadow mb-4">
        <div class="card-header">
            <?php
                switch($request->type){
                    case 'transfer':
                        $from = \App\Models\Location::find($request->location_from);
                        $to = \App\Models\Location::find($request->location_to);
                        $m = "\\App\\Models\\".ucfirst($request->model_type);
                        $model = $m::find($request->model_id);
                        $name = $model->name ?? ucfirst($request->model_type);
                        echo "Transfer {$name} from {$from->name} to {$to->name}";
                        break;
                    case 'disposal':
                        echo "Dispose of Asset";
                        break;
                    default:
                        echo "Unknown Request";
                        break;
                }
            ?>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-sm-4 col-md-2 col-lg-1">
                    @php($user = \App\Models\User::find($request->user_id))
                    @if($user->photo()->exists())
                    <img class="img-profile rounded-circle"
                         src="{{ asset($user->photo->path) ?? asset('images/profile.png') }}" width="100%">
                    @else
                        <img class="img-profile rounded-circle" src="{{ asset('images/profile.png') }}" width="100%">
                    @endif
                </div>
                <div class="col-12 col-sm-8 col-md-10 col-lg-11">
                    <p>{{ $request->notes }}</p>
                    <small class="text-info">Requested by {{ $user->name }} on {{ \Carbon\Carbon::parse($request->created_at)->format("d/m/Y - H:i:s")}}</small>
                </div>
            </div>
            <hr>
            @if($request->status == 0)
            <a class="btn btn-sm btn-green m-1" href="{{ route('request.handle', [$request->id, '1'])}}"><i class="fas fa-check"></i> Approve</a>
            <a class="btn btn-sm btn-coral m-1" href="{{ route('request.handle', [$request->id, '2'])}}"><i class="fas fa-times"></i> Reject</a>
            @elseif($request->status == 1)
                @php($super = \App\Models\User::find($request->super_id))
                <small class="text-success"><i class="fas fa-check-circle"></i> This was approved by {{ $super->name}} on {{ \Carbon\Carbon::parse($request->updated_at)->format("d/m/Y - H:i:s")}}</small>
            @else
                @php($super = \App\Models\User::find($request->super_id))
                <small class="text-danger"><i class="fas fa-minus-circle"></i> This was rejected by {{ $super->name}} on {{ \Carbon\Carbon::parse($request->updated_at)->format("d/m/Y - H:i:s")}}</small>
            @endif
        </div>
    </div>
    @endforeach

    {{ $requests->links() }} --}}
</section>

@endsection

@section('modals')

@endsection

@section('js')

@endsection
