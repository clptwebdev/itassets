@extends('layouts.app')

@section('title', 'View Logs')


@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">View All System Logs</h1>
        <div>
            <a href="/" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm loading"><i
                    class="fas fa-download fa-sm text-white-50"></i> Back to Dashboard</a>
            @if($logs->count() > 0)
                @can('delete' , \App\Models\Log::class)
                    <a href="{{route('logs.destroy')}}"
                       class="d-none d-sm-inline-block btn btn-sm btn-coral shadow-sm deleteBtn"><i
                            class="fas fa-trash fa-sm text-white-50"></i> Delete All logs</a>
                @endcan
            @endif
            @can('viewAny' , \App\Models\Log::class)
                @if($logs->count() > 1)
                    <form class="d-inline-block" action="/exportlogs" method="POST">
                        @csrf
                        <input type="hidden" value="{{ json_encode($logs->pluck('id'))}}" name="logs"/>
                        <button type="submit" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm loading">
                            <i class="fas fa-download fa-sm text-dark-50"></i> Export
                        </button>
                    </form>
                @endif
            @endcan
        </div>
    </div>
    <x-handlers.alerts/>
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
                                   @if(session()->has('log_search')) value="{{ session('log_search') }}" @endif>
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
                                <option value="auth" @if(session('log_type') == "auth") selected @endif>auth</option>
                                <option value="manufacturer"
                                        @if(session('log_type') == "manufacturer") selected @endif >manufacturer
                                </option>
                                <option value="assetModel" @if(session('log_type') == "assetModel") selected @endif>
                                    assetModel
                                </option>
                                <option value="location" @if(session('log_type') == "location") selected @endif>
                                    location
                                </option>
                                <option value="asset" @if(session('log_type') == "asset") selected @endif>asset</option>
                                <option value="supplier" @if(session('log_type') == "supplier") selected @endif>
                                    supplier
                                </option>
                                <option value="accessory" @if(session('log_type') == "accessory") selected @endif>
                                    accessory
                                </option>
                                <option value="user" @if(session('log_type') == "user") selected @endif>user</option>
                                <option value="miscellanea" @if(session('log_type') == "miscellanea") selected @endif>
                                    miscellanea
                                </option>
                                <option value="component" @if(session('log_type') == "component") selected @endif>
                                    component
                                </option>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-blue">Search</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            @if(session()->has('log_search') || session()->has('log_type'))
                <div class="col-4">
                    <a href="{{route('logs.clear.filter')}}" class="btn btn-warning shadow-sm">Clear Filter</a>
                </div>
            @endif
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
                    <x-paginate :model="$logs"/>
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



