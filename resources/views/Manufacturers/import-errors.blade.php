@extends('layouts.app')

@section('title', 'Import Errors')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')

    {{--    <form action="/manufacturers/create/import" method="POST">--}}

    <div class="d-sm-flex align-items-center justify-content-between mb-4"><?php  ?>
        <h1 class="h3 mb-0 text-gray-800">Import
            Failures</h1>
        
        <div>
            <a href="{{route("manufacturers.index")}}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                    class="fas fa-chevron-left fa-sm text-white-50">
                </i> Back to Manufacturers</a>
            <a id="import" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50 fa-text-width"></i> Importing Help</a>
            <button onclick="javscript:checkErrors(this);" class="d-inline-block btn btn-sm btn-success shadow-sm">
                <i class="far fa-save fa-sm text-white-50"></i> Save
            </button>
        </div>
    </div>
    @php $errorRows = '';foreach($errorArray as $id => $key){ $errorRows = !empty($errorRows)? $errorRows.', '.$id:$id;}  @endphp
    <div class="m-3 alert alert-danger">You have several errors Within your Import in rows
        <div class="col-md-12">
            <div id="summary">
                <p class="collapse" id="collapseSummary">{{$errorRows}}</p>
                <a class="collapsed" data-toggle="collapse" href="#collapseSummary" aria-expanded="false"
                   aria-controls="collapseSummary"></a>
            </div>
        </div>
    </div>

    @if(session('danger_message'))
        <div class="alert alert-danger"> {{ session('danger_message')}} </div>
    @endif

    @if(session('success_message'))
        <div class="alert alert-success"> {{ session('success_message')}} </div>
    @endif

    <section>
        <p class="mb-4">Below are the different Import Failures of all the different assets stored in the management
            system. Each has
            displays the amount of different assets that are assigned the category.</p>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">

                    <table id="categoryTable" class="table table-striped">

                        <thead>
                        <tr>
                            <th class="col-4">Manufacturers Name</th>
                            <th>Support Url</th>
                            <th>Support Number</th>
                            <th>Support Email</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="col-4">Manufacturers Name</th>
                            <th>Support Url</th>
                            <th>Support Number</th>
                            <th>Support Email</th>
                        </tr>
                        </tfoot>
                        @csrf
                        @php($line = 0)
                        @foreach($errorArray as $row =>$error)
                            <?php $errors = explode(",", $error); ?>
                            <tr>
                                <td>
                                    <span id="name{{$line}}" class="tooltip-danger">
                                    <input type="text"
                                           class="form-control <?php if (in_array('name', $errors)) {?>border-danger<?php }?>
                                               "
                                           name="name[]"
                                           id="name" value="{{ $valueArray[$row]['name'] }}"
                                           placeholder="This Row is Empty Please Fill!" required data-container='#name{{$line}}' data-placement='top'
                                           @if(array_key_exists('name', $errorValues[$row])) {!! "data-toggle='tooltip' title='{$errorValues[$row]['name']}'" !!}@endif>
                                    </span>
                                <td>
                                    <span id="supporturl{{$line}}" class="tooltip-danger">
                                    <input type="text"
                                           class="form-control <?php if (in_array('supporturl', $errors)) {?>border-danger<?php }?>"
                                           name="supportUrl[]"
                                           id="supportUrl" value="{{ $valueArray[$row]['supporturl'] }}" data-container='#supporturl{{$line}}' data-placement='top'
                                           @if(array_key_exists('supporturl', $errorValues[$row])) {!! "data-toggle='tooltip' title='{$errorValues[$row]['supporturl']}'" !!}@endif>
                                    </span>
                                </td>
                                <td>
                                    <span id="supportphone{{$line}}" class="tooltip-danger">
                                    <input type="text"
                                           class="form-control <?php if (in_array('supportphone', $errors)) {?>border-danger<?php }?>"
                                           name="supportPhone[]"
                                           id="supportPhone" placeholder="This Row is Empty Please Fill!"
                                           value="{{ $valueArray[$row]['supportphone'] }}" required data-container='#supportphone{{$line}}' data-placement='top'
                                           @if(array_key_exists('supportphone', $errorValues[$row])) {!! "data-toggle='tooltip' title='{$errorValues[$row]['supportphone']}'" !!}@endif>
                                    </span>
                                </td>
                                <td>
                                    <span id="supportemail{{$line}}" class="tooltip-danger">
                                    <input type="text"
                                           class="form-control <?php if (in_array('supportemail', $errors)) {?>border-danger<?php }?>"
                                           name="supportEmail[]"
                                           id="supportEmail" placeholder="This Row is Empty Please Fill!"
                                           value="{{ $valueArray[$row]['supportemail'] }}" required data-container='#supportemail{{$line}}' data-placement='top'
                                           @if(array_key_exists('supportemail', $errorValues[$row])) {!! "data-toggle='tooltip' title='{$errorValues[$row]['supportemail']}'" !!}@endif>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>

    </section>

@endsection

@section('modals')
    <div class="modal fade bd-example-modal-lg" id="importManufacturerModal" tabindex="-1" role="dialog"
         aria-labelledby="importManufacturerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importManufacturerModalLabel">Importing Data Help</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="/importmanufacturer" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <h2 class="h3 mb-0 text-gray-800">Requirements needed to finish your import</h2>
                        <ol>
                            <li>The Required fields are: Name, Supplier,Location and serial num.</li>
                            <li>All Correct rows skip this page and import straight to the database so please don't
                                re-import your file!
                            </li>
                            <li>Struggling to Pass this stage are all your data fields in the correct format?</li>
                        </ol>
                    </div>
                    <div class="modal-footer">
                        <p>For Anymore information please email Apollo@clpt.co.uk</p>
                        <a href="https://clpt.sharepoint.com/:x:/s/WebDevelopmentTeam/ERE4_YTdj09OgTKDE0rqW5cBA2GpiFOsH-ziakd4zeYYwA?e=JBx4b4"
                           target="_blank" class="btn btn-info">
                            Download Import Template
                        </a>
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    @csrf
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')

    <script type="text/javascript">

        $('#import').click(function () {
            $('#manufacturer-id-test').val($(this).data('id'))
            //showModal
            $('#importManufacturerModal').modal('show')

        })

        function checkErrors(obj) {

            var token = $("[name='_token']").val();
            var data = new FormData();
            data.append('_token', token);

            //Names
            var inputs = $("input[name='name[]']").get();
            inputs.forEach(element => {
                data.append('name[]', element.value);
            });

            //Url
            var urlInputs = $("input[name='supportUrl[]']").get();
            urlInputs.forEach(element => {
                data.append('supportUrl[]', element.value);
            });

            //Phone
            var telInputs = $("input[name='supportPhone[]']").get();
            telInputs.forEach(element => {
                data.append('supportPhone[]', element.value);
            });

            //Email
            var emInputs = $("input[name='supportEmail[]']").get();
            emInputs.forEach(element => {
                data.append('supportEmail[]', element.value);
            });

            $.ajax({
                url: '/manufacturers/create/ajax',
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response === 'Success') {
                        window.location.href = '/manufacturers';
                    } else {
                        $('.form-control').removeClass('border-danger');
                        $('.form-control').tooltip('dispose');
                        $('input').removeClass('border-danger');
                        var i = 0;
                        Object.entries(response).forEach(entry => {
                            const [key, value] = entry;
                            res = key.split('.');
                            const error = value.toString().replace(key, res[0]);
                            $(`[name='${res[0]}[]']:eq(${res[1]})`).addClass('border');
                            $(`[name='${res[0]}[]']:eq(${res[1]})`).addClass('border-danger');
                            $(`[name='${res[0]}[]']:eq(${res[1]})`).attr('data-toggle', 'tooltip');
                            $(`[name='${res[0]}[]']:eq(${res[1]})`).attr('title', error);
                            $(`[name='${res[0]}[]']:eq(${res[1]})`).tooltip();
                            i++;
                        });
                        $('.alert.alert-danger').html(`There were ${i} errors in the following rows`);
                    }
                },
            });
        }
    </script>

@endsection
