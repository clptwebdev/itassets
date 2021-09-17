@props(["miscellaneou"])
<div class="col-12 col-lg-6 mb-4">
    <div class="card shadow h-100 pb-2" style="border-left: 0.25rem solid #666;">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold">Miscellanea Log</h6>
        </div>
        <div class="card-body">
            <div class="row no-gutters">
                <div class="col mr-2">
                    <div class="mb-1">
                        <p class="mb-4">Log information regarding {{ $miscellaneou->name}}
                            , view history and activity regarding the selected miscellanea.</p>
                        <table class="logs table table-striped ">
                                <tbody>
                                @foreach($miscellaneou->logs()->orderBy('created_at', 'desc')->take(5)->get() as $log)
                                    <tr>
                                        <td class="text-left"><small>{{$log->data}}<br><span class="text-info">{{ $log->user->name }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $log->created_at, 'Europe/London');}}</span></small></td>
                                    </tr>
                                @endforeach
                                </tbody>
                        </table>
                        <a href="{{route("logs.index")}}" class="btn btn-blue">View All Logs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
