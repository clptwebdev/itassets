@extends('layouts.app')

@section('title', 'Machinery Import Errors')



@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4"><?php  ?>
        <h1 class="h3 mb-0 text-gray-800">Import Failures</h1>
        @php $errorRows = '';foreach($errorArray as $id => $key){ $errorRows = !empty($errorRows)? $errorRows.', '.$id:$id;}  @endphp

        <div>
            <form action="{{route('machinery.export.import')}}" method="POST" class="d-inline">
                @csrf
                <div class="form-group">
                    <input type="hidden" class="form-control " name="name" id="name" placeholder=""
                           value="{{json_encode($valueArray)}}">
                </div>
                <button type="submit" class="d-inline-block btn btn-sm btn-yellow shadow-sm loading"><i
                        class="far fa-save fa-sm text-dark-50"></i> Download Errors
                </button>
            </form>
            <a href="{{ route('machineries.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                    class="fas fa-chevron-left fa-sm te
                    xt-white-50"></i> Back to Software</a>
            <a id="importHelpBtn" class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50 fa-text-width"></i> Importing Help</a>
            <a onclick="javscript:checkErrors(this);" class="d-inline-block btn btn-sm btn-green shadow-sm"><i
                    class="far fa-save fa-sm text-white-50"></i> Save
            </a>
        </div>
    </div>

    <div class="m-3 alert alert-danger">You have several errors Within your Import in rows
        <div class="col-md-12">
            <div id="summary">
                <p class="collapse" id="collapseSummary">{{$errorRows}}</p>
                <a class="collapsed" data-bs-toggle="collapse" href="#collapseSummary" aria-expanded="false"
                   aria-controls="collapseSummary"></a>
            </div>
        </div>
    </div>

    <x-handlers.alerts/>

    <section>
        <p class="mb-4">There were some errors in your csv file that you have tried to upload to the system. Any rows
                        that had valid information will have already been
                        uploaded. If there a lot of returned errors you can choose to download them all in excel format
                        allowing you to retry with only the failed rows. </p>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">

                    <table id="categoryTable" class="table table-bordered">
                        <thead>
                        <tr>
                            <th><small>Name</small></th>
                            <th><small>Serial Num</small></th>
                            <th><small>Purchased Cost</small></th>
                            <th><small>Donated</small></th>
                            <th><small>Purchased Date</small></th>
                            <th><small>Supplier</small></th>
                            <th><small>Manufacturers</small></th>
                            <th><small>Order_no</small></th>
                            <th><small>Warranty</small></th>
                            <th><small>Depreciation</small></th>
                            <th><small>Location</small></th>
                            <th><small>Description</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th><small>Name</small></th>
                            <th><small>Serial Num</small></th>
                            <th><small>Purchased Cost</small></th>
                            <th><small>Donated</small></th>
                            <th><small>Purchased Date</small></th>
                            <th><small>Supplier</small></th>
                            <th><small>Manufacturers</small></th>
                            <th><small>Order_no</small></th>
                            <th><small>Warranty</small></th>
                            <th><small>Depreciation</small></th>
                            <th><small>Location</small></th>
                            <th><small>Description</small></th>
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
                                                   class="import-control @if(in_array('name', $errors)){{ 'border-bottom border-danger'}}@endif"
                                                   name="name[]" value="{{ $valueArray[$row]['name'] }}"
                                                   placeholder="This Row is Empty Please Fill!"
                                                   data-bs-container='#name{{$line}}' data-bs-placement='top'
                                               @if(array_key_exists('name', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['name']}'" !!}@endif>
                                        </span>
                                </td>
                                <td>
                                    <span id="serial_no{{$line}}" class="tooltip-danger">
                                    <input type="text"
                                           class="import-control @if(in_array('serial_no', $errors)){{ 'border-bottom border-danger'}}@endif"
                                           name="serial_no[]" id="serial_no"
                                           placeholder="This Row is Empty Please Fill!"
                                           value="{{ $valueArray[$row]['serial_no'] }}"
                                           data-bs-container='#serial_no{{$line}}' data-bs-placement='top'
                                           @if(array_key_exists('serial_no', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['serial_no']}'" !!}@endif
                                    >
                                    </span>
                                </td>
                                <td>
                                    <span id="purchased_cost{{$line}}" class="tooltip-danger">
                                    <input type="text"
                                           class="import-control @if(in_array('purchased_cost', $errors)){{'border-bottom border-danger'}}@endif"
                                           name="purchased_cost[]" id="purchased_cost"
                                           placeholder="This Row is Empty Please Fill!"
                                           value="{{ $valueArray[$row]['purchased_cost'] }}" required
                                           data-bs-container='#purchased_cost{{$line}}' data-bs-placement='top'
                                           @if(array_key_exists('purchased_cost', $errorValues[$row])) {!! "data-bs-toggle='tooltip'  title='{$errorValues[$row]['purchased_cost']}'" !!}@endif
                                    >
                                    </span>
                                </td>
                                <td>
                                    <span id="donated{{$line}}" class="tooltip-danger">
                                        <select type="dropdown"
                                                class="import-control <?php if (in_array('donated', $errors)) {?>border-bottom border-danger<?php }?>"
                                                name="donated[]" id="donatedInput{{$line}}" onchange="getFields(this);"
                                                autocomplete="off" required data-bs-container='#donated{{$line}}'
                                                data-bs-placement='top'
                                                @if(array_key_exists('donated', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['donated']}'" !!}@endif>>
                                            <option value="0" @if($valueArray[$row]['donated'] == 0){{'selected'}}@endif>No</option>
                                            <option
                                                value="1" @if( $valueArray[$row]['donated'] == 1){{'selected'}}@endif>Yes</option>
                                        </select>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    try
                                    {
                                        $date = \Carbon\Carbon::parse(str_replace('/', '-', $valueArray[$row]['purchased_date']))->format('Y-m-d');
                                    } catch(\Exception $e)
                                    {
                                        $date = 'dd/mm/yyyy';
                                    }
                                    ?>
                                    <span id="purchased_date{{$line}}" class="tooltip-danger">
                                        <input type="date"
                                               class="import-control @if(in_array('purchased_date', $errors)){{ 'border-bottom border-danger'}}@endif"
                                               name="purchased_date[]" id="purchased_date"
                                               placeholder="This Row is Empty Please Fill!" value="{{ $date }}" required
                                               data-bs-container='#purchased_date{{$line}}' data-bs-placement='top'
                                            @if(array_key_exists('purchased_date', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['purchased_date']}'" !!}
                                                @endif
                                            >
                                        </span>

                                </td>
                                <td>
                                        <span id="supplier_id{{$line}}" class="tooltip-danger">
                                        <select type="dropdown"
                                                class="import-control @if(in_array('supplier_id', $errors)){{ 'border-bottom border-danger'}}@endif"
                                                name="supplier_id[]" required data-bs-container='#supplier_id{{$line}}'
                                                data-bs-placement='top'
                                        @if(array_key_exists('supplier_id', $errorValues[$row])) {!! "data-bs-toggle='tooltip'  title='{$errorValues[$row]['supplier_id']}'" !!}@endif
                                        >
                                            <option
                                                value="0" @if($valueArray[$row]['supplier_id'] == ''){{'selected'}}@endif>No Supplier</option>
                                            @foreach($suppliers as $supplier)
                                                <option
                                                    value="{{ $supplier->id }}" @if(  strtolower($valueArray[$row]['supplier_id']) ==  strtolower($supplier->name)){{'selected'}}@endif>{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                        </span>
                                </td>
                                <td>
                                        <span id="manufacturer_id{{$line}}" class="tooltip-danger">
                                        <select type="dropdown"
                                                class="import-control @if(in_array('manufacturer_id', $errors)){{ 'border-bottom border-danger'}}@endif"
                                                name="manufacturer_id[]" required
                                                data-bs-container='#manufacturer_id{{$line}}' data-bs-placement='top'
                                        @if(array_key_exists('manufacturer_id', $errorValues[$row])) {!! "data-bs-toggle='tooltip'  title='{$errorValues[$row]['manufacturer_id']}'" !!}@endif
                                        >
                                            <option
                                                value="0" @if($valueArray[$row]['manufacturer_id'] == ''){{'selected'}}@endif>Please Select a Manufacturer</option>
                                            @foreach($manufacturers as $manufacturer)
                                                <option
                                                    value="{{$manufacturer->id }}" @if( $valueArray[$row]['manufacturer_id'] == $manufacturer->name){{'selected'}}@endif>{{ $manufacturer->name }}</option>
                                            @endforeach
                                        </select>
                                        </span>
                                </td>
                                <td>
                                        <span id="order_no{{$line}}" class="tooltip-danger">
                                        <input type="text"
                                               class="import-control @if(in_array('order_no', $errors)){{ 'border-bottom border-danger'}}@endif"
                                               name="order_no[]" id="order_no"
                                               placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['order_no'] }}" required
                                               data-bs-container='#order_no{{$line}}' data-bs-placement='top'
                                            @if(array_key_exists('order_no', $errorValues[$row])) {!! "data-bs-toggle='tooltip'  title='{$errorValues[$row]['order_no']}'" !!}@endif
                                        >
                                        </span>
                                </td>
                                <td>
                                    <span id="warranty{{$line}}" class="tooltip-danger">
                                    <input type="text"
                                           class="import-control @if(in_array('warranty', $errors)){{'border-bottom border-danger'}}@endif"
                                           name="warranty[]" id="warranty" placeholder="This Row is Empty Please Fill!"
                                           value="{{ $valueArray[$row]['warranty'] }}" required
                                           data-bs-container='#warranty{{$line}}' data-bs-placement='top'
                                           @if(array_key_exists('warranty', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['warranty']}'" !!}@endif
                                    >
                                    </span>
                                </td>
                                <td>
                                    <span id="depreciation{{$line}}" class="tooltip-danger">
                                        <input type="text"
                                               class="import-control @if(in_array('depreciation', $errors)){{ 'border-bottom border-danger'}}@endif"
                                               name="depreciation[]"
                                               value="{{ $valueArray[$row]['depreciation'] }}"
                                               placeholder="This Row is Empty Please Fill!"
                                               data-bs-container='#depreciation{{$line}}' data-bs-placement='top'
                                           @if(array_key_exists('depreciation', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['depreciation']}'" !!}@endif>
                                    </span>
                                </td>
                                <td>
                                    <span id="location_id{{$line}}" class="tooltip-danger">
                                    <select type="dropdown"
                                            class="import-control @if(in_array('location_id', $errors)){{ 'border-bottom border-danger'}}@endif"
                                            name="location_id[]" required data-bs-container='#location_id{{$line}}'
                                            data-bs-placement='top'
                                    @if(array_key_exists('location_id', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['location_id']} - {$valueArray[$row]['location_id']}'" !!}@endif
                                    >
                                        <option
                                            value="0" @if($valueArray[$row]['location_id'] == ''){{'selected'}}@endif>Please Select a Location</option>
                                        @foreach($locations as $location)
                                            <option
                                                value="{{ $location->id  }}" @if( $valueArray[$row]['location_id'] == $location->name){{'selected'}}@endif>{{ $location->name }}</option>
                                        @endforeach
                                    </select>
                                    </span>
                                </td>
                                <td>
                                        <span id="description{{$line}}" class="tooltip-danger">
                                        <input type="text"
                                               class="import-control @if(in_array('description', $errors)){{'border-bottom border-danger'}}@endif"
                                               name="description[]" id="description" placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['description'] }}" required
                                               data-bs-container='#description{{$line}}' data-bs-placement='top'
                                               @if(array_key_exists('description', $errorValues[$row])) {!! "data-bs-toggle='tooltip'  title='{$errorValues[$row]['description']}'" !!}@endif
                                               >
                                        </span>
                                </td>
                            </tr>
                            @php($line++)@endforeach{{--    </form>--}}</tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>

@endsection

@section('modals')
    <div class="modal fade bd-example-modal-lg" id="importHelpModal" tabindex="-1" role="dialog"
         aria-labelledby="importHelpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importHelpModalLabel">Importing Data Help</h5>
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
                        <a href="https://clpt.sharepoint.com/:x:/s/WebDevelopmentTeam/ERgeo9FOFaRIvmBuTRVcvycBkiTnqHf3aowELiOt8Hoi1Q?e=CXfTdb"
                           target="_blank" class="btn btn-blue">
                            Download Import Template
                        </a>
                        <button class="btn btn-grey" type="button" data-bs-dismiss="modal">Cancel</button>
                    @csrf
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        const importModal = new bootstrap.Modal(document.getElementById('importHelpModal'));
        const importHelpBtn = document.querySelector('#importHelpBtn');

        importHelpBtn.addEventListener('click', function () {
            importModal.show();
        });

        function enableToolTips() {
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(t => {
                new bootstrap.Tooltip(t);
            })
        }

        enableToolTips();


        const importControl = document.querySelectorAll('.import-control');

        const errorMessage = document.querySelector('.alert.alert-danger');


        //validation
        function checkErrors(obj) {

            const token = document.querySelector("[name='_token']").value;
            const data = new FormData();
            data.append('_token', token);

            //Names
            const inputs = document.querySelectorAll("input[name='name[]']");
            inputs.forEach(element => {
                data.append('name[]', element.value);
            });
            //supplier
            const supplierInputs = document.querySelectorAll("select[name='supplier_id[]']");
            supplierInputs.forEach(element => {
                data.append('supplier_id[]', element.value);
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
            const dpInputs = document.querySelectorAll("input[name='depreciation[]']");
            dpInputs.forEach(element => {
                data.append('depreciation[]', element.value);
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
            const descriptionInputs = document.querySelectorAll("input[name='description[]']");
            descriptionInputs.forEach(element => {
                data.append('description[]', element.value);
            });


            const xhr = new XMLHttpRequest()

            xhr.onload = function () {
                if (xhr.responseText === 'Success') {
                    window.location.href = '/machineries';
                } else {
                    importControl.forEach((item) => {
                        item.classList.remove('border-bottom', 'border-danger');
                    });

                    let i = 0;
                    console.log(xhr.response);
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
                        elements[num].setAttribute('data-bs-original-title', error);
                        new bootstrap.Tooltip(elements[num]);
                        i++;
                        enableToolTips();
                    });

                    errorMessage.innerHTML = `There were ${i} errors in the following rows`;
                }
            };

            xhr.open("POST", "/import/machinery/errors");
            xhr.send(data);
        }

    </script>
@endsection
