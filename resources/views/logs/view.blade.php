@extends('layouts.app')

@section('title', 'View Logs')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">View All System Logs</h1>
        <div>
            <a href="/" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm loading"><i
                    class="fas fa-download fa-sm text-white-50"></i> Back to Dashboard</a>
            @if($logs->count() > 0)
                <a href="{{route('logs.destroy')}}"
                   class="d-none d-sm-inline-block btn btn-sm btn-coral shadow-sm deleteBtn"><i
                        class="fas fa-trash fa-sm text-white-50"></i> Delete All logs</a>
            @endif
            @if($logs->count() > 1)
                <form class="d-inline-block" action="/exportlogs" method="POST">
                    @csrf
                    <input type="hidden" value="{{ json_encode($logs->pluck('id'))}}" name="logs"/>
                    <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm loading"><i
                            class="fas fa-download fa-sm text-dark-50"></i> Export
                    </button>
                </form>
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
        <p class="mb-4">Below are the different Logs of Processes that have taken place stored in the management
            system.</p>
        <!-- DataTales Example -->
        <div class="row">
            <div id="searchBar" class="col-4">
                <div class="col-auto">
                    <label class="sr-only" for="inlineFormInputGroup">Search</label>
                    <form method="POST" action="{{route('logs.filter')}}">
                        <div class="input-group mb-2">
                            @csrf
                            <input type="text" class="form-control" name="search" placeholder="Search"
                                   @if(session()->has('search')) value="{{ session('search') }}" @endif>
                            <div class="input-group-append">
                                <button class="btn btn-blue">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div id="searchBar" class="col-4">
                <div class="col-auto">
                    <label class="sr-only" for="inlineFormInputGroup">Filter</label>
                    <form method="POST" action="{{route('logs.filter')}}">
                        @csrf
                        <div class="input-group mb-2 d-flex">
                            <select class="form-control" name="type">
                                <option value="auth" selected>auth</option>
                                <option value="manufacturer" selected>manufacturer</option>
                                <option value="assetModel" selected>assetModel</option>
                                <option value="location" selected>location</option>
                                <option value="asset" selected>asset</option>
                                <option value="supplier" selected>supplier</option>
                                <option value="accessory" selected>accessory</option>
                                <option value="user" selected>user</option>
                                <option value="miscellanea" selected>miscellanea</option>
                                <option value="component" selected>component</option>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-blue">Search</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <div class="col-4">
                <a href="{{route('logs.clear.filter')}}" class="btn btn-warning shadow-sm">Clear Filter</a>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="logsTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th><small>Data</small></th>
                            <th><small>User</small></th>
                            <th><small>Type</small></th>
                            <th><small> ID</small></th>
                            <th class="text-center"><small>Date</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th><small>Data</small></th>
                            <th><small>User</small></th>
                            <th><small>Type</small></th>
                            <th><small> ID</small></th>
                            <th class="text-center"><small>Date</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td class="text-left text-sm">{{ $log->data }}</td>
                                <td class="text-left text-sm">@if($log->user()->exists()) {{ $log->user->name ?? 'Authentication'}} @else {{ 'Authentication' }} @endif</td>
                                <td>{{ $log->loggable_type}}</td>
                                <td>{{ $log->loggable_id }}</td>
                                <td class="text-center">{{ $log->updated_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-between align-content-center">
                        <div>
                            @if($logs->hasPages())
                                {{ $logs->links()}}
                            @endif
                        </div>
                        <div class="text-right">
                            Showing Assets {{ $logs->firstItem() }} to {{ $logs->lastItem() }} ({{ $logs->total() }}
                            Total Results)
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-3">
            <div class="card-body">
                <h4>Help with Logs</h4>
                <p>This area can be minimised and will contain a little help on the page that the user is currently
                    on.</p>
            </div>
        </div>

    </section>

@endsection

@section('modals')

@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"
            integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function () {
            $('#logsTable').DataTable({
                "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                }],
                "order": [[4, "asc"]]
            });
        });
    </script>
@endsection
