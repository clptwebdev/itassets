@extends('layouts.app')@section('title', 'View Manufacturer Import errors')

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
            @can('viewAny' , \App\Models\Manufacturer::class)
                <a href="{{route("manufacturers.index")}}"
                   class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                        class="fas fa-chevron-left fa-sm text-white-50">
                    </i> Back to Manufacturers</a>
            @endcan
            <a id="import" class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50 fa-text-width"></i> Importing Help</a>
            <button onclick="javscript:checkErrors(this);" class="d-inline-block btn btn-sm btn-green shadow-sm">
                <i class="far fa-save fa-sm text-white-50"></i> Save
            </button>
        </div>
    </div>
    @php $errorRows = '';foreach($errorArray as $id => $key){ $errorRows = !empty($errorRows)? $errorRows.', '.$id:$id;}  @endphp
    <div class="m-3 alert alert-danger">You have several errors Within your Import in rows
        <div class="col-md-12">
            <div id="summary">
                <p class="collapse" id="collapseSummary">{{$errorRows}}</p>
                <a class="collapsed" data-bs-toggle="collapse" href="#collapseSummary" aria-expanded="false"
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
                                           class="form-control <?php if (in_array('name', $errors)) {?>border-danger<?php }?>"
                                           name="name[]" id="name" value="{{ $valueArray[$row]['name'] }}"
                                           placeholder="This Row is Empty Please Fill!" required
                                           data-bs-container='#name{{$line}}' data-bs-placement='top'
                                           @if(array_key_exists('name', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['name']}'" !!}@endif>
                                    </span>
                                <td>
                                    <span id="supporturl{{$line}}" class="tooltip-danger">
                                    <input type="text"
                                           class="form-control <?php if (in_array('supporturl', $errors)) {?>border-danger<?php }?>"
                                           name="supportUrl[]" id="supportUrl"
                                           value="{{ $valueArray[$row]['supporturl'] }}"
                                           data-bs-container='#supporturl{{$line}}' data-bs-placement='top'
                                           @if(array_key_exists('supporturl', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['supporturl']}'" !!}@endif>
                                    </span>
                                </td>
                                <td>
                                    <span id="supportphone{{$line}}" class="tooltip-danger">
                                    <input type="text"
                                           class="form-control <?php if (in_array('supportphone', $errors)) {?>border-danger<?php }?>"
                                           name="supportPhone[]" id="supportPhone"
                                           placeholder="This Row is Empty Please Fill!"
                                           value="{{ $valueArray[$row]['supportphone'] }}" required
                                           data-bs-container='#supportphone{{$line}}' data-bs-placement='top'
                                           @if(array_key_exists('supportphone', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['supportphone']}'" !!}@endif>
                                    </span>
                                </td>
                                <td>
                                    <span id="supportemail{{$line}}" class="tooltip-danger">
                                    <input type="text"
                                           class="form-control <?php if (in_array('supportemail', $errors)) {?>border-danger<?php }?>"
                                           name="supportEmail[]" id="supportEmail"
                                           placeholder="This Row is Empty Please Fill!"
                                           value="{{ $valueArray[$row]['supportemail'] }}" required
                                           data-bs-container='#supportemail{{$line}}' data-bs-placement='top'
                                           @if(array_key_exists('supportemail', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['supportemail']}'" !!}@endif>
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
                    <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
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
                            <li>Need More help? Click <a href="{{route("documentation.index").'#collapseSevenImport'}}">here</a>
                                to be redirected to the Documentation on Importing!
                            </li>

                        </ol>
                    </div>
                    <div class="modal-footer">
                        <p>For Anymore information please email Apollo@clpt.co.uk</p>
                        <a href="https://clpt.sharepoint.com/:x:/s/WebDevelopmentTeam/ERE4_YTdj09OgTKDE0rqW5cBA2GpiFOsH-ziakd4zeYYwA?e=JBx4b4"
                           target="_blank" class="btn btn-info">
                            Download Import Template
                        </a>
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    @csrf
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')

    <script type="text/javascript">


        const importModal = new bootstrap.Modal(document.getElementById('importManufacturerModal'));
        const importHelpBtn = document.querySelector('#import');

        importHelpBtn.addEventListener('click', function () {
            importModal.show();
        });

        function enableToolTips() {
            let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        }

        enableToolTips();


        //validation
        function checkErrors(obj) {

            const importControl = document.querySelectorAll('.import-control');

            const errorMessage = document.querySelector('.alert.alert-danger');

            const token = document.querySelector("[name='_token']").value;
            const data = new FormData();
            data.append('_token', token);

            //Names
            const inputs = document.querySelectorAll("input[name='name[]']");
            inputs.forEach(element => {
                data.append('name[]', element.value);
            });
            //Url
            const urlInputs = document.querySelectorAll("input[name='supportUrl[]']");
            urlInputs.forEach(element => {
                data.append('supportUrl[]', element.value);
            });
            //Phone
            const phoneInputs = document.querySelectorAll("input[name='supportPhone[]']");
            phoneInputs.forEach(element => {
                data.append('supportPhone[]', element.value);
            });
            //Email
            const emailInputs = document.querySelectorAll("input[name='supportEmail[]']");
            emailInputs.forEach(element => {
                data.append('supportEmail[]', element.value);
            });

            const xhr = new XMLHttpRequest()

            xhr.onload = function () {
                if (xhr.responseText === 'Success') {
                    window.location.href = '/manufacturers';
                } else {
                    importControl.forEach((item) => {
                        item.classList.remove('border-bottom', 'border-danger');
                    });

                    let i = 0;
                    Object.entries(JSON.parse(xhr.responseText)).forEach(entry => {
                        console.log(entry);
                        const [key, value] = entry;
                        res = key.split('.');
                        const error = value.toString().replace(key, res[0]);
                        console.log(error);
                        console.log(res[1]);
                        let elements = document.querySelectorAll(`[name='${res[0]}[]']`);
                        console.log(elements[0]);
                        let num = parseInt(res[1]);
                        elements[num].classList.add('border-bottom', 'border-danger');
                        elements[num].setAttribute('data-bs-toggle', 'tooltip');
                        elements[num].setAttribute('data-title', error);
                        i++;
                        enableToolTips();
                    });

                    errorMessage.innerHTML = `There were ${i} errors in the following rows`;
                }
            };

            xhr.open("POST", "/manufacturers/create/ajax");
            xhr.send(data);
        }
    </script>

@endsection
