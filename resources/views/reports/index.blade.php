@extends('layouts.app')

@section('title', 'View all Assets')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css"
          integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.theme.min.css"
          integrity="sha512-9h7XRlUeUwcHUf9bNiWSTO9ovOWFELxTlViP801e5BbwNJ5ir9ua6L20tEroWZdm+HFBAWBLx2qH4l4QHHlRyg=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endsection

@section('content')

    @if(session('danger_message'))
        <div class="alert alert-danger"> {!!session('danger_message')!!} </div>
    @endif

    @if(session('success_message'))
        <div class="alert alert-success"> {!! session('success_message')!!} </div>
    @endif

    <section>
        <p class="mb-4">Below are all the Reports generated by the Users. These reports are available for 7 days before they are automatically deleted, please print or download
            prior to the expiry date.
        </p>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="assetsTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th class="col-5"><small>Report</small></th>
                            <th class="col-2 col-md-auto"><small>Generated By:</small></th>
                            <th class="col-2 col-md-auto text-center"><small>Date</small></th>
                            <th class="col-2 text-center"><small>Expiry</small></th>
                            <th class="col-1 text-right"><small>Options</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="col-5"><small>Report</small></th>
                            <th class="col-2 col-md-auto"><small>Generated By:</small></th>
                            <th class="col-2 col-md-auto text-center"><small>Date</small></th>
                            <th class="col-2 text-center"><small>Expiry</small></th>
                            <th class="col-1 text-right"><small>Options</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @foreach($reports as $report)
                            <tr>
                                <td>
                                    @if(file_exists($report->report))
                                        <a href="{{ asset($report->report)}}" title="New">{{$report->report }}</a>
                                    @else
                                        @if(\Carbon\Carbon::now()->floatDiffInMinutes($report->created_at) < 15)
                                            {!! $report->report.' <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>' !!}
                                        @else
                                            {!! "<span class='text-coral'>{$report->report} <i class='fas fa-times'></i></span>" !!}
                                        @endif
                                    @endif
                                </td>
                                <td>{{ $report->user->name ?? 'N/A' }}</td>
                                <td class="text-center" data-sort="{{  strtotime($report->created_at) }}">{{ \Carbon\Carbon::parse($report->created_at)->format('d/m/Y H:i:s')}}</td>
                                <td class="text-center" data-sort="{{ strtotime($report->created_at)+(60*60*24*7)}}">{{ \Carbon\Carbon::parse($report->created_at)->addDays(7)->format('d/m/Y H:i:s') }}</td>
                                <td class="text-right">...</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow mb-3">
            <div class="card-body">
                <h4>Help with Assets</h4>
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
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script>
        

        $(document).ready(function () {
            $('#assetsTable').DataTable({
                "autoWidth": false,
                "pageLength": 25,
                "columnDefs": [{
                    "targets": [4],
                    "orderable": false
                }],
                "order": [[2, "desc"]],
            });
        });
    </script>
@endsection
