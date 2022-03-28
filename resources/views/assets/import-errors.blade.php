@extends('layouts.app')@section('title', 'View Assets Import errors')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4"><?php  ?>
        <h1 class="h3 mb-0 text-gray-800">Import Failures</h1>
        <div>
            <form action="assets/export-import-errors" method="POST">
                @csrf
                <div class="form-group">
                    <input type="hidden" class="import-control " name="asset_tag" id="asset_tag" placeholder=""
                           value="{{htmlspecialchars(json_encode($valueArray))}}">
                </div>
                @can('viewAll' , \App\Models\Asset::class)
                    <button type="submit" class="d-inline-block btn btn-sm btn-yellow shadow-sm"><i
                            class="far fa-save fa-sm text-white-50"></i> Save All Errors as Excel
                    </button>
                @endcan
                @can('viewAll' ,\App\Models\Asset::class)
                    <a href="{{ route('assets.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                            class="fas fa-chevron-left fa-sm text-white-50"></i> Back to assets</a>
                @endcan
                <a id="import" class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm"><i
                        class="fas fa-download fa-sm text-white-50 fa-text-width"></i> Importing Help</a>
                <a onclick="javscript:checkErrors(this);" class="d-inline-block btn btn-sm btn-green shadow-sm">
                    <i class="far fa-save fa-sm text-dark-50"></i> Save
                </a>
            </form>
        </div>
    </div>

    @php $errorRows = '';foreach($errorArray as $id => $key){ $errorRows = !empty($errorRows)? $errorRows.', '.$id:$id;}  @endphp
    <div class="m-3 alert alert-danger">
        You
        have @if(count($errorArray) > 1){{ count($errorArray).' errors'}}@else {{  count($errorArray).' error' }}@endif
        within your Assets Import. You have errors
        on rows {{ $errorRows}}

    </div>
    <x-handlers.alerts/>
    <section>
        <p class="mb-4">Below are the different Import Failures of all the different assets stored in the management
                        system. Each has
                        displays the amount of different assets that are assigned the category. If There are many errors
                        please go back and revise the changes in
                        excel Using the export errors function.Else use editor below!</p>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="categoryTable" class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center"><small>Asset Tag</small></th>
                            <th class="text-center"><small>Name</small></th>
                            <th class="text-center"><small>Serial Num</small></th>
                            <th class="text-center"><small>Asset Model</small></th>
                            <th class="text-center"><small>Status</small></th>
                            <th class="text-center"><small>Purchased Date</small></th>
                            <th class="text-center"><small>Purchased Cost</small></th>
                            <th class="text-center"><small>Donated</small>/small></th>
                            <th class="text-center"><small>Supplier</small></th>
                            <th class="text-center"><small>Order No</small></th>
                            <th class="text-center"><small>Warranty</small></th>
                            <th class="text-center"><small>Location</small></th>
                            <th class="text-center"><small>Audit Date</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th class="text-center"><small>Asset Tag</small></th>
                            <th class="text-center"><small>Name</small></th>
                            <th class="text-center"><small>Serial Num</small></th>
                            <th class="text-center"><small>Asset Model</small></th>
                            <th class="text-center"><small>Status</small></th>
                            <th class="text-center"><small>Purchased Date</small></th>
                            <th class="text-center"><small>Purchased Cost</small></th>
                            <th class="text-center"><small>Donated</small></th>
                            <th class="text-center"><small>Supplier</small></th>
                            <th class="text-center"><small>Order No</small></th>
                            <th class="text-center"><small>Warranty</small></th>
                            <th class="text-center"><small>Location</small></th>
                            <th class="text-center"><small>Audit Date</small></th>
                        </tr>
                        </tfoot>

                        @csrf
                        @php($line = 0)
                        @foreach($errorArray as $row =>$error)
                            <?php $errors = explode(",", $error); ?>
                            <tr>
                                <td>
                                    <span id="asset_tag{{$line}}" class="tooltip-danger">
                                        <input type="text" maxlength="11"
                                               class="import-control <?php if (in_array('asset_tag', $errors)) {?>border-bottom border-danger<?php }?>"
                                               name="asset_tag[]" id="asset_tag"
                                               value="{{ $valueArray[$row]['asset_tag'] }}"
                                               placeholder="This Row is Empty Please Fill!" required
                                               data-bs-container='#asset_tag{{$line}}' data-bs-placement='top'
                                           @if(array_key_exists('asset_tag', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['asset_tag']}'" !!}@endif>
                                    </span>
                                </td>
                                <td>
                                    <span id="name{{$line}}" class="tooltip-danger">
                                        <input type="text" maxlength="11"
                                               class="import-control <?php if (in_array('name', $errors)) {?>border-bottom border-danger<?php }?>"
                                               name="name[]" id="name" value="{{ $valueArray[$row]['name'] }}"
                                               placeholder="This Row is Empty Please Fill!" required
                                               data-bs-container='#name{{$line}}' data-bs-placement='top'
                                           @if(array_key_exists('name', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['name']}'" !!}@endif>
                                    </span>
                                </td>
                                <td>
                                    <span id="serial_no{{$line}}" class="tooltip-danger">
                                        <input type="text"
                                               class="import-control <?php if (in_array('serial_no', $errors)) {?>border-bottom border-danger<?php }?>"
                                               name="serial_no[]" id="serial_no"
                                               value="{{ $valueArray[$row]['serial_no'] }}"
                                               placeholder="This Row is Empty Please Fill!" required
                                               data-bs-container='#serial_no{{$line}}' data-bs-placement='top'
                                           @if(array_key_exists('serial_no', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['serial_no']}'" !!}@endif>
                                    </span>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <span id="asset_model{{$line}}" class="tooltip-danger">
                                            <select type="dropdown"
                                                    class="import-control <?php if (in_array('asset_model', $errors)) {?>border-bottom border-danger<?php }?>"
                                                    name="asset_model[]" id="asset_model" onchange="getFields(this);"
                                                    autocomplete="off" required
                                                    data-bs-container='#asset_model{{$line}}' data-bs-placement='top'
                                                    @if(array_key_exists('asset_model', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['asset_model']}'" !!}@endif>
                                                <option
                                                    value="0" @if($valueArray[$row]['asset_model'] == ''){{'selected'}}@endif>
                                                    Please Select a Model
                                                </option>
                                                @foreach($models as $model)
                                                    <option
                                                        value="{{ $model->id }}" @if( $valueArray[$row]['asset_model'] == $model->name){{'selected'}}@endif>{{ $model->name }}</option>
                                                @endforeach
                                            </select>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span id="status_id{{$line}}" class="tooltip-danger">
                                        <select type="dropdown"
                                                class="import-control <?php if (in_array('status_id', $errors)) {?>border-bottom border-danger<?php }?>"
                                                name="status_id[]" id="status_id" onchange="getFields(this);"
                                                autocomplete="off" required data-bs-container='#status_id{{$line}}'
                                                data-bs-placement='top'
                                                @if(array_key_exists('status_id', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['status_id']}'" !!}@endif>>
                                            <option
                                                value="0" @if($valueArray[$row]['status_id'] == ''){{'selected'}}@endif>
                                                Please Select a Status
                                            </option>
                                            @foreach($statuses as $status)
                                                <option
                                                    value="{{ $status->id }}" @if( $valueArray[$row]['status_id'] == $status->name){{'selected'}}@endif>{{ $status->name }}</option>
                                            @endforeach
                                        </select>
                                    </span>
                                </td>
                                <td>
                                    <span id="purchased_date{{$line}}" class="tooltip-danger">
                                        <?php
                                        try
                                        {
                                            $date = \Carbon\Carbon::parse(str_replace('/', '-', $valueArray[$row]['purchased_date']))->format('Y-m-d');
                                        } catch(\Exception $e)
                                        {
                                            $date = 'dd/mm/yyyy';
                                        }
                                        ?>
                                        <input type="date"
                                               class="import-control <?php if (in_array('purchased_date', $errors)) {?>border-bottom border-danger<?php }?>"
                                               name="purchased_date[]" id="purchased_date"
                                               placeholder="This Row is Empty Please Fill!" value="{{  $date}}" required
                                               data-bs-container='#purchased_date{{$line}}' data-bs-placement='top'
                                           @if(array_key_exists('purchased_date', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['purchased_date']}'" !!}@endif>
                                    </span>
                                </td>
                                <td>
                                    <span id="purchased_cost{{$line}}" class="tooltip-danger">
                                    <input type="text"
                                           class="import-control <?php if (in_array('purchased_cost', $errors)) {?>border-bottom border-danger<?php }?>"
                                           name="purchased_cost[]" id="purchased_cost"
                                           placeholder="This Row is Empty Please Fill!"
                                           value="{{ $valueArray[$row]['purchased_cost'] }}" required
                                           data-bs-container='#purchased_cost{{$line}}' data-bs-placement='top'
                                           @if(array_key_exists('purchased_cost', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['purchased_cost']}'" !!}@endif>
                                    </span>
                                </td>
                                <td>
                                    <span id="donated{{$line}}" class="tooltip-danger">
                                        <select type="dropdown"
                                                class="import-control <?php if (in_array('status_id', $errors)) {?>border-bottom border-danger<?php }?>"
                                                name="donated[]" id="donatedInput{{$line}}" onchange="getFields(this);"
                                                autocomplete="off" required data-bs-container='#donated{{$line}}'
                                                data-bs-placement='top'
                                                @if(array_key_exists('donated', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['donated']}'" !!}@endif>>
                                            <option value="0" @if($valueArray[$row]['donated'] == 0){{'selected'}}@endif>No</option>
                                            <option
                                                value="1" @if( $valueArray[$row]['status_id'] == 1){{'selected'}}@endif>Yes</option>
                                        </select>
                                    </span>
                                </td>
                                <td>
                                    <span id="supplier_id{{$line}}" class="tooltip-danger">
                                    <select type="dropdown"
                                            class="import-control <?php if (in_array('supplier_id', $errors)) {?>border-bottom border-danger<?php }?>"
                                            name="supplier_id[]" id="supplier_id" onchange="getFields(this);"
                                            autocomplete="off" required data-bs-container='#supplier_id{{$line}}'
                                            data-bs-placement='top'
                                            @if(array_key_exists('supplier_id', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['supplier_id']}'" !!}@endif>
                                        <option
                                            value="0" @if($valueArray[$row]['supplier_id'] == ''){{'selected'}}@endif>
                                            Please Select a Model
                                        </option>
                                        @foreach($suppliers as $supplier)
                                            <option
                                                value="{{ $supplier->id }}" @if( $valueArray[$row]['supplier_id'] == $supplier->name){{'selected'}}@endif>{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <span id="order_no{{$line}}" class="tooltip-danger">
                                        <input type="text"
                                               class="import-control <?php if (in_array('order_no', $errors)) {?>border-bottom border-danger<?php }?>"
                                               name="order_no[]" id="order_no"
                                               placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['order_no'] }}"
                                               requireddata-bs-container='#order_no{{$line}}' data-bs-placement='top'
                                           @if(array_key_exists('order_no', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['order_no']}'" !!}@endif>
                                    </span>
                                </td>
                                <td>
                                    <span id="warranty{{$line}}" class="tooltip-danger">
                                        <input type="text"
                                               class="import-control <?php if (in_array('warranty', $errors)) {?>border-bottom border-danger<?php }?>"
                                               name="warranty[]" id="warranty"
                                               placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['warranty'] }}" required
                                               data-bs-container='#warranty{{$line}}' data-bs-placement='top'
                                            @if(array_key_exists('warranty', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['warranty']}'" !!}@endif>
                                    </span>
                                </td>
                                <td>
                                    <span id="location_id{{$line}}" class="tooltip-danger">
                                        <select type="dropdown"
                                                class="import-control <?php if (in_array('location_id', $errors)) {?>border-bottom border-danger<?php }?>"
                                                name="location_id[]" id="location_id" onchange="getFields(this);"
                                                autocomplete="off" required data-bs-container='#location_id{{$line}}'
                                                data-bs-placement='top'
                                                @if(array_key_exists('location_id', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['location_id']}'" !!}@endif>
                                            <option
                                                value="0" @if($valueArray[$row]['location_id'] == ''){{'selected'}}@endif>
                                                Please Select a Model
                                            </option>
                                            @foreach($locations as $location)
                                                <option
                                                    value="{{ $location->id}}" @if( $valueArray[$row]['location_id'] == $location->name){{'selected'}}@endif>{{ $location->name }}</option>
                                            @endforeach
                                        </select>
                                    </span>
                                </td>
                                <td>
                                    <span id="audit_date{{$line}}" class="tooltip-danger">
                                        <?php
                                        try
                                        {
                                            $date = \Carbon\Carbon::parse(str_replace('/', '-', $valueArray[$row]['audit_date']))->format('Y-m-d');
                                        } catch(\Exception $e)
                                        {
                                            $date = 'dd/mm/yyyy';
                                        }
                                        ?>
                                        <input type="date"
                                               class=" import-control <?php if (in_array('audit_date', $errors)) {?>border-bottom border-danger<?php }?>"
                                               name="audit_date[]" id="audit_date"
                                               placeholder="This Row is Empty Please Fill!" value="{{ $date}}" required
                                               data-bs-container='#audit_date{{$line}}' data-bs-placement='top'
                                           @if(array_key_exists('audit_date', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['audit_date']}'" !!}@endif>
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
                        <a href="https://clpt.sharepoint.com/:x:/s/WebDevelopmentTeam/Eb2RbyCNk_hOuTfMOufGpMsBl0yUs1ZpeCjkCm6YnLfN9Q?e=HDDCIp"
                           target="_blank" class="btn btn-blue">
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
    <script>
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

            //Asset Tag
            const asset_tagInputs = document.querySelectorAll("input[name='asset_tag[]']");
            asset_tagInputs.forEach(element => {
                data.append('asset_tag[]', element.value);
            });

            //Model
            const modelInputs = document.querySelectorAll("select[name='asset_model[]']");
            modelInputs.forEach(element => {

                data.append('asset_model[]', element.value);
            });
            //supplier
            const supplierInputs = document.querySelectorAll("select[name='supplier_id[]']");
            supplierInputs.forEach(element => {
                data.append('supplier_id[]', element.value);
            });
            //status
            const statusInputs = document.querySelectorAll("select[name='status_id[]']");
            statusInputs.forEach(element => {
                data.append('status_id[]', element.value);
            });
            //Manufacturer
            const mfInputs = document.querySelectorAll("select[name='manufacturer_id[]']");
            mfInputs.forEach(element => {
                data.append('manufacturer_id[]', element.value);
            });

            //Location
            const loInputs = document.querySelectorAll("select[name='location_id[]']");
            loInputs.forEach(element => {
                data.append('location_id[]', element.value);
            });

            //Purchased Cost
            const pcInputs = document.querySelectorAll("input[name='purchased_cost[]']");
            pcInputs.forEach(element => {
                data.append('purchased_cost[]', element.value);
            });

            //Purchased Date
            const pdInputs = document.querySelectorAll("input[name='purchased_date[]']");
            pdInputs.forEach(element => {
                data.append('purchased_date[]', element.value);
            });

            //Depreciation
            const dpInputs = document.querySelectorAll("select[name='depreciation_id[]']");
            dpInputs.forEach(element => {
                data.append('depreciation_id[]', element.value);
            });
            //ROOM
            const roomInputs = document.querySelectorAll("input[name='room[]']");
            roomInputs.forEach(element => {
                data.append('room[]', element.value);
            });
            //Order Number
            const orderNoInputs = document.querySelectorAll("input[name='order_no[]']");
            orderNoInputs.forEach(element => {
                data.append('order_no[]', element.value);
            });

            //Serial Number
            const serialNoInputs = document.querySelectorAll("input[name='serial_no[]']");
            serialNoInputs.forEach(element => {
                data.append('serial_no[]', element.value);
            });
            //Donated
            const donInputs = document.querySelectorAll("select[name='donated[]']");
            donInputs.forEach(element => {
                data.append('donated[]', element.value);
            });
            //Warranty
            const warInputs = document.querySelectorAll("input[name='warranty[]']");
            warInputs.forEach(element => {
                data.append('warranty[]', element.value);
            });
            //Notes
            const notesInputs = document.querySelectorAll("input[name='notes[]']");
            notesInputs.forEach(element => {
                data.append('notes[]', element.value);
            });

            const xhr = new XMLHttpRequest()

            xhr.onload = function () {
                if (xhr.responseText === 'Success') {
                    window.location.href = '/assets';
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

            xhr.open("POST", "/assets/create/ajax");
            xhr.send(data);
        }
    </script>
@endsection
