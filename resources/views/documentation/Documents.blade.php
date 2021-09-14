@extends('layouts.app')

@section('title', 'View Components')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>

@endsection

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Help</h1>
        <div>
            <a href="/" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50 fa-text-width"></i> Back to Dashboard</a>
        </div>
    </div>

    @if(session('danger_message'))
        <div class="alert alert-danger"> {!! session('danger_message')!!} </div>
    @endif

    @if(session('success_message'))
        <div class="alert alert-success"> {!! session('success_message')!!} </div>
    @endif
{{--    <div id="accordion">--}}
{{--        <div class="card">--}}
{{--            <div class="card-header bg-gray-200" id="headingOne">--}}
{{--                <h5 class="mb-0 text-center">--}}
{{--                    <button class="btn btn-link font-weight-bold " data-toggle="collapse" data-target="#collapseOne"--}}
{{--                            aria-expanded="true" aria-controls="collapseOne">--}}
{{--                        Asset Help--}}
{{--                    </button>--}}
{{--                </h5>--}}
{{--            </div>--}}

{{--            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">--}}
{{--                <div class="card-body">--}}
{{--                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3--}}
{{--                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum--}}
{{--                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla--}}
{{--                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt--}}
{{--                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer--}}
{{--                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus--}}
{{--                    labore sustainable VHS.--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="card">--}}
{{--            <div class="card-header" id="headingTwoo">--}}
{{--                <h5 class="mb-0">--}}
{{--                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwoo"--}}
{{--                            aria-expanded="false" aria-controls="collapseTwo">--}}
{{--                        Collapsible Group Item #2--}}
{{--                    </button>--}}
{{--                </h5>--}}
{{--            </div>--}}
{{--            <div id="collapseTwoo" class="collapse" aria-labelledby="headingTwoo" data-parent="#accordion">--}}
{{--                <div class="card-body">--}}
{{--                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3--}}
{{--                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum--}}
{{--                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla--}}
{{--                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt--}}
{{--                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer--}}
{{--                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus--}}
{{--                    labore sustainable VHS.--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="card">--}}
{{--            <div class="card-header" id="headingThree">--}}
{{--                <h5 class="mb-0">--}}
{{--                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree"--}}
{{--                            aria-expanded="false" aria-controls="collapseThree">--}}
{{--                        Collapsible Group Item #3--}}
{{--                    </button>--}}
{{--                </h5>--}}
{{--            </div>--}}
{{--            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">--}}
{{--                <div class="card-body">--}}
{{--                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3--}}
{{--                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum--}}
{{--                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla--}}
{{--                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt--}}
{{--                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer--}}
{{--                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus--}}
{{--                    labore sustainable VHS.--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="card">--}}
{{--            <div class="card-header" id="headingFour">--}}
{{--                <h5 class="mb-0">--}}
{{--                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour"--}}
{{--                            aria-expanded="false" aria-controls="collapseThree">--}}
{{--                        Collapsible Group Item #3--}}
{{--                    </button>--}}
{{--                </h5>--}}
{{--            </div>--}}
{{--            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">--}}
{{--                <div class="card-body">--}}
{{--                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3--}}
{{--                    wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum--}}
{{--                    eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla--}}
{{--                    assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt--}}
{{--                    sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer--}}
{{--                    farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus--}}
{{--                    labore sustainable VHS.--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    <section>
        <div id="accordion">
            <div id="headingOne">
                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">test1</button>
                </div>
                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                        wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                        eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                        assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                        sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                        farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                        labore sustainable VHS.
                    </div>
                </div>
                <div id="headingTwo">
                    <button id="active" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">test2</button>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body">
                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3
                        wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum
                        eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla
                        assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt
                        sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                        farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus
                        labore sustainable VHS.
                    </div>
                </div>
            </div>
        </section>




    @endsection

    @section('modals')

    @endsection

    @section('js')
        <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
        <script>
            $(function () {
                $("#accordion").accordion();
            });
        </script>

    @endsection
