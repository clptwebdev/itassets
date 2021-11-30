@extends('layouts.app')

@section('title', 'Dashboard')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')
    <!-- session messages -->
    <x-handlers.alerts/>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    @if($assets->count() != 0)
        <!-- Asset stats -->
        <x-admin.asset-info :transfers="$transfers" :archived="$archived" :assets="$assets" :accessories="$accessories" :components="$components" :consumables="$consumables" :miscellaneous="$miscellaneous" :requests="$requests"/>

        <!-- Content Row -->
{{--        <x-piecharts.pie :assets="$assets" :statuses="$statuses"/>--}}

        <!-- location stats and block -->
{{--        <x-locations.locations_all :locations="$locations"/>--}}

        <!-- Category_status info tables stats -->
{{--        <x-categories_status_info :statuses="$statuses" :category="$category"/>--}}
    @endif
@endsection

@section('js')
    <script src="{{ asset('js/chart.js') }}"></script>
    <script src="{{ asset('js/demo/chart-bar-demo.js') }}"></script>
    <script>
        // $(document).ready(function () {
        //     showGraph();
        //     showValueGraph();
        //     showAssetGraph();
        // });
        //
        //
        // function showGraph() {
        //     $.ajax({
        //         url: 'chart/pie/locations',
        //         success: function (data) {
        //             var as = JSON.parse(data);
        //             var name = [];
        //             var icon = [];
        //             var assets = [];
        //
        //             for (var i in as) {
        //                 name.push(as[i].name);
        //                 icon.push(as[i].icon);
        //                 assets.push(as[i].asset);
        //             }
        //
        //             var chartdata = {};
        //
        //             var ctx = document.getElementById("myPieChart");
        //             var myPieChart = new Chart(ctx, {
        //                 type: 'doughnut',
        //                 data: {
        //                     labels: name,
        //                     datasets: [{
        //                         label: 'Asset Sources',
        //                         backgroundColor: icon,
        //                         borderColor: '#46d5f1',
        //                         hoverBackgroundColor: '#CCCCCC',
        //                         hoverBorderColor: '#666666',
        //                         data: assets
        //                     }],
        //                 },
        //
        //             });
        //             ctx.height = 500;
        //
        //         },
        //         error: function () {
        //             console.log('Eror');
        //         },
        //     });
        // }
        //
        // function showValueGraph() {
        //     $.ajax({
        //         url: 'chart/asset/values',
        //         success: function (data) {
        //             var as = JSON.parse(data);
        //             var dataSets = [];
        //             for (var i in as) {
        //                 var data = [];
        //                 for (var d in as[i]['years']) {
        //                     data.push(as[i]['years'][d]);
        //                 }
        //                 dataSets.push({
        //                     label: as[i]['name'],
        //                     backgroundColor: as[i]['icon'],
        //                     borderColor: '#46d5f1',
        //                     hoverBackgroundColor: '#CCCCCC',
        //                     hoverBorderColor: '#666666',
        //                     data: data,
        //                 });
        //             }
        //
        //             var ctx = document.getElementById("valueBarChart");
        //             var myPieChart = new Chart(ctx, {
        //                 type: 'bar',
        //                 data: {
        //                     labels: [2021, 2022, 2023, 2024],
        //                     datasets: dataSets,
        //                 },
        //                 options: {
        //                     responsive: true,
        //                     scales: {
        //                         x: {
        //                             stacked: true,
        //                         },
        //                         y: {
        //                             stacked: true
        //                         }
        //                     }
        //                 }
        //             });
        //             ctx.height = 500;
        //         },
        //         error: function () {
        //             console.log('Eror');
        //         },
        //     });
        // }
        //
        // function showAssetGraph() {
        //     $.ajax({
        //         url: 'chart/asset/audits',
        //         success: function (data) {
        //             var as = JSON.parse(data);
        //             var dataSets = [];
        //             for (var i in as) {
        //                 dataSets.push({
        //                     label: as[i]['name'],
        //                     backgroundColor: as[i]['icon'],
        //                     borderColor: as[i]['icon'],
        //                     hoverBackgroundColor: '#CCCCCC',
        //                     hoverBorderColor: '#666666',
        //                     data: [as[i]['past'], as[i]['month'], as[i]['quarter'], as[i]['half']],
        //                 });
        //             }
        //
        //             var ctx = document.getElementById("assetLineChart");
        //             var myPieChart = new Chart(ctx, {
        //                 type: 'line',
        //                 data: {
        //                     labels: ['Overdue', 'Less than a Month', 'Due in 1-3 Months', 'Due in 4-6 Months'],
        //                     datasets: dataSets,
        //                 },
        //                 options: {
        //                     responsive: true,
        //                     scales: {
        //                         x: {
        //                             stacked: true,
        //                         },
        //                         y: {
        //                             stacked: true
        //                         }
        //                     }
        //                 }
        //             });
        //
        //             ctx.height = 500;
        //         },
        //         error: function () {
        //             console.log('Eror');
        //         },
        //     });
        // }

    </script>

    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable();
        });
    </script>
@endsection
