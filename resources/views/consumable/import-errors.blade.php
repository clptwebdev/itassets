@extends('layouts.app')

@section('title', 'View Consumable Import errors')

@section('content')
    {{--    <form action="/components/create/import" method="POST">--}}

    <div class="d-sm-flex align-items-center justify-content-between mb-4"><?php  ?>
        <h1 class="h3 mb-0 text-gray-800">Import Failures</h1>
        @php $errorRows = '';foreach($errorArray as $id => $key){ $errorRows = !empty($errorRows)? $errorRows.', '.$id:$id;}  @endphp

        <div>
            <form action="consumables/export-import-errors" method="POST">
                @csrf
                <div class="form-group">
                    <input type="hidden" class="form-control " name="name" id="name" placeholder=""
                           value="{{htmlspecialchars(json_encode($valueArray))}}">
                </div>
                <button type="submit" class="d-inline-block btn btn-sm btn-yellow shadow-sm loading"><i
                        class="far fa-save fa-sm text-white-50"></i> Download Errors
                </button>

                <a href="{{ route('consumables.index')}}"
                   class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i class="fas fa-chevron-left fa-sm te
                        xt-white-50"></i> Back to Consumables</a>
                <a id="import" class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm"><i
                        class="fas fa-download fa-sm text-white-50 fa-text-width"></i> Importing Help</a>
                <a onclick="javscript:checkErrors(this);" class="d-inline-block btn btn-sm btn-green shadow-sm"><i
                        class="far fa-save fa-sm text-white-50"></i> Save
                </a>
            </form>
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
        <p class="mb-4">Below are the different Import Failures of all the different assets stored in the management
                        system. Each has
                        displays the amount of different assets that are assigned the category.</p>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">

                    <table id="categoryTable" class="table table-bordered">
                        <thead>
                        <tr>
                            <th><small>Name</small></th>
                            <th><small>Status</small></th>
                            <th><small>Supplier</small></th>
                            <th><small>Manufacturers</small></th>
                            <th><small>Location</small></th>
                            <th><small>Order_no</small></th>
                            <th><small>Serial No</small></th>
                            <th><small>Purchased Cost</small></th>
                            <th><small>Purchased Date</small></th>
                            <th><small>Warranty</small></th>
                            <th><small>Notes</small></th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th><small>Name</small></th>
                            <th><small>Status</small></th>
                            <th><small>Supplier</small></th>
                            <th><small>Manufacturers</small></th>
                            <th><small>Location</small></th>
                            <th><small>Order_no</small></th>
                            <th><small>Serial No</small></th>
                            <th><small>Purchased Cost</small></th>
                            <th><small>Purchased Date</small></th>
                            <th><small>Warranty</small></th>
                            <th><small>Notes</small></th>
                        </tr>
                        </tfoot>
                        <tbody>
                        @csrf
                        @php($line = 0)
                        @foreach($errorArray as $row =>$error)
                            <?php $errors = explode(",", $error); ?>
                            <tr>
                                <td>
                                        <span id="name{{$line}}" class="tooltip-danger">
                                            <input type="text"
                                                   class="import-control @if(in_array('name', $errors)){{ 'border-bottom border-danger'}}@endif"
                                                   name="name[]" id="name" value="{{ $valueArray[$row]['name'] }}"
                                                   placeholder="This Row is Empty Please Fill!" required
                                                   data-bs-container='#name{{$line}}' data-bs-placement='top'
                                               @if(array_key_exists('name', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['name']}'" !!}@endif>
                                        </span>
                                </td>
                                <td>
                                        <span id="status_id{{$line}}" class="tooltip-danger">
                                        <select type="dropdown"
                                                class="import-control @if(in_array('status_id', $errors)){{ 'border-bottom border-danger'}}@endif"
                                                name="status_id[]" id="status_id" required
                                                data-bs-container='#status_id{{$line}}' data-bs-placement='top'
                                        @if(array_key_exists('status_id', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['status_id']}'" !!}@endif
                                        >
                                            <option
                                                value="0" @if($valueArray[$row]['status_id'] == ''){{'selected'}}@endif>No Status</option>
                                            @foreach($statuses as $status)
                                                <option
                                                    value="{{$status->id }}" @if( $valueArray[$row]['status_id'] == $status->name){{'selected'}}@endif>{{ $status->name }}</option>
                                            @endforeach
                                        </select>

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
                                        <span id="location_id{{$line}}" class="tooltip-danger">
                                        <select type="dropdown"
                                                class="import-control @if(in_array('location_id', $errors)){{ 'border-bottom border-danger'}}@endif"
                                                name="location_id[]" required data-bs-container='#location_id{{$line}}'
                                                data-bs-placement='top'
                                        @if(array_key_exists('location_id', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['location_id']}'" !!}@endif
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
                                        <span id="serial_no{{$line}}" class="tooltip-danger">
                                        <input type="text"
                                               class="import-control @if(in_array('serial_no', $errors)){{ 'border-bottom border-danger'}}@endif"
                                               name="serial_no[]" id="serial_no"
                                               placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['serial_no'] }}" required
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
                                               value="{{ preg_replace('/[[:^print:]]/', '', $valueArray[$row]['purchased_cost'])  }}"
                                               required data-bs-container='#purchased_cost{{$line}}'
                                               data-bs-placement='top'
                                               @if(array_key_exists('purchased_cost', $errorValues[$row])) {!! "data-bs-toggle='tooltip'  title='{$errorValues[$row]['purchased_cost']}'" !!}@endif
                                        >
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
                                        <span id="warranty{{$line}}" class="tooltip-danger">
                                        <input type="text"
                                               class="import-control @if(in_array('warranty', $errors)){{'border-bottom border-danger'}}@endif"
                                               name="warranty[]" id="warranty"
                                               placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['warranty'] }}" required
                                               data-bs-container='#warranty{{$line}}' data-bs-placement='top'
                                               @if(array_key_exists('warranty', $errorValues[$row])) {!! "data-bs-toggle='tooltip' title='{$errorValues[$row]['warranty']}'" !!}@endif
                                        >
                                        </span>
                                </td>
                                <td>
                                        <span id="warranty{{$line}}" class="tooltip-danger">
                                        <input type="text"
                                               class="import-control @if(in_array('notes', $errors)){{'border-bottom border-danger'}}@endif"
                                               name="notes[]" id="notes" placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['notes'] }}" required
                                               data-bs-container='#notes{{$line}}' data-bs-placement='top'
                                               @if(array_key_exists('notes', $errorValues[$row])) {!! "data-bs-toggle='tooltip'  title='{$errorValues[$row]['notes']}'" !!}@endif
                                               >
                                        </span>
                                </td>
                            </tr>
                            @php($line++)
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('modals')
    <x-modals.import/>
@endsection

@section('js')
    <script>

        const importModal = new bootstrap.Modal(document.getElementById('importManufacturerModal'));
        const importHelpBtn = document.querySelector('#import');

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
            //status
            const statusInputs = document.querySelectorAll("select[name='status_id[]']");
            statusInputs.forEach(element => {
                data.append('status_id[]', element.value);
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
                    window.location.href = '/consumables';
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
                        elements[num].setAttribute('data-bs-original-title', error);
                        new bootstrap.Tooltip(elements[num]);
                        i++;
                        enableToolTips();
                    });

                    errorMessage.innerHTML = `There were ${i} errors in the following rows`;
                }
            };

            xhr.open("POST", "/consumables/create/ajax");
            xhr.send(data);
        }
    </script>
@endsection
