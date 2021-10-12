@extends('layouts.app')

@section('title', 'View Logs')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet" />
@endsection

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">View All System Logs</h1>
        <div>
                <a href="/" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm loading"><i
                        class="fas fa-download fa-sm text-white-50"></i> Back to Dashboard</a>
            @if($logs->count() > 1)
{{--                @can('viewAny', auth()->user())--}}
                    <form class="d-inline-block" action="/exportlogs" method="POST">
                        @csrf
                        <input type="hidden" value="{{ json_encode($logs->pluck('id'))}}" name="logs"/>
                        <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm loading"><i
                                class="fas fa-download fa-sm text-dark-50"></i> Export</button>
                    </form>
{{--                @endcan--}}
            @endif
        </div>
    </div>
    @if(session('danger_message'))
        <div class="alert alert-danger"> {!!session('danger_message')!!} </div>
    @endif

    @if(session('success_message'))
        <div class="alert alert-success"> {!! session('success_message')!!} </div>
    @endif
    <section>
        <p class="mb-4">Below are the different Logs of Processes that have taken place stored in the management system.</p>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="logsTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="text-center"><small>Data</small></th>
                            <th><small>User</small></th>
                            <th><small>Type</small></th>
                            <th class="text-center"><small> ID</small></th>
                            <th><small>Date</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th><small>Data</small></th>
                            <th><small>User</small></th>
                            <th><small>Type</small></th>
                            <th class="text-center"><small> ID</small></th>
                            <th><small>Date</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td class="text-left text-sm">{{ $log->data }}</td>
                                <td class="text-left text-sm">{{ $log->user->name ?? 'Authentication'}}</td>
                                <td>{{ $log->loggable_type}}</td>
                                <td>{{ $log->loggable_id }}</td>
                                <td class="text-center">{{ $log->updated_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow mb-3">
            <div class="card-body">
                <h4>Help with Logs</h4>
                <p>This area can be minimised and will contain a little help on the page that the user is currently on.</p>
            </div>
        </div>

    </section>

@endsection

@section('modals')

@endsection

@section('js')
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    <script>
    $(document).ready( function () {
        $('#logsTable').DataTable({
            "columnDefs": [ {
                "targets": [3,5],
                "orderable": false,
            } ],
            "order": [[ 1, "asc"]]
        });
    } );
</script>
@endsection
