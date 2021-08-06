@extends('layouts.app')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')
{{--    <form action="/components/create/import" method="POST">--}}

        <div class="d-sm-flex align-items-center justify-content-between mb-4"><?php  ?>
            <h1 class="h3 mb-0 text-gray-800">Import
                Failures</h1>@php $errorRows = '';foreach($errorArray as $id => $key){ $errorRows = !empty($errorRows)? $errorRows.', '.$id:$id;}  @endphp
            <div class="m-3 alert alert-danger">You have several errors Within your Import in rows
                <div class="col-md-12">
                    <div id="summary">
                        <p class="collapse" id="collapseSummary">{{$errorRows}}</p>
                        <a class="collapsed" data-toggle="collapse" href="#collapseSummary" aria-expanded="false"
                           aria-controls="collapseSummary"></a>
                    </div>
                </div>
            </div>
            <div>
                <form action="components/export-import-errors" method="POST">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" class="form-control " name="name"
                               id="name" placeholder="" value="{{htmlspecialchars(json_encode($valueArray))}}">
                    </div>
                    <button type="submit" class="d-inline-block btn btn-sm btn-warning shadow-sm"><i
                            class="far fa-save fa-sm text-white-50"></i> Save All Errors as Excel
                    </button>

                    <a href="/components" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                            class="fas fa-plus fa-sm te
                        xt-white-50"></i> Back to Components</a>
                    <a id="import" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                            class="fas fa-download fa-sm text-white-50 fa-text-width"></i> Importing Help</a>
                    <a onclick="javscript:checkErrors(this);" class="d-inline-block btn btn-sm btn-success shadow-sm"><i
                            class="far fa-save fa-sm text-white-50"></i> Save
                    </a>
                </form>
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
                                <th>Name</th>
                                <th>Status Id</th>
                                <th>Supplier</th>
                                <th>Manufacturers</th>
                                <th>Location</th>
                                <th>Order_no</th>
                                <th>Serial Num</th>
                                <th>Purchased Cost</th>
                                <th>Purchased Date</th>
                                <th>Warranty</th>
                                <th>Notes</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Status id</th>
                                <th>Supplier</th>
                                <th>Manufacturers</th>
                                <th>Location</th>
                                <th>Order_no</th>
                                <th>Serial Num</th>
                                <th>Purchased Cost</th>
                                <th>Purchased Date</th>
                                <th>Warranty</th>
                                <th>Notes</th>
                            </tr>
                            </tfoot>

                            @csrf
                            @foreach($errorArray as $row =>$error)
                                <?php $errors = explode(",", $error); ?>
                                <tr>
                                    <td>
                                        <input type="text"
                                               class="form-control <?php if (in_array('name', $errors)) {?>border-danger<?php }?>
                                                   "
                                               name="name[]"
                                               id="name" value="{{ $valueArray[$row]['name'] }}"
                                               placeholder="This Row is Empty Please Fill!" required>
                                        @if(array_key_exists('name', $errorValues[$row]))<small class="text-danger text-capitalize">{{$errorValues[$row]['name']}}</small>@endif
                                    </td>
                                    <td>
                                        <select type="dropdown" class="form-control" name="status_id[]" id="status_id" onchange="getFields(this);" autocomplete="off" required>
                                            <option value="0" @if($valueArray[$row]['status_id'] == ''){{'selected'}}@endif>Please Select a Model</option>
                                            @foreach($statuses as $status)
                                                <option value="{{$status->id }}" @if( $valueArray[$row]['status_id'] == $status->name){{'selected'}}@endif>{{ $status->name }}</option>
                                            @endforeach
                                        </select>
                                        @if(array_key_exists('status_id', $errorValues[$row]))<small class="text-danger text-capitalize">{{$errorValues[$row]['status_id']}}</small>@endif
                                    </td>
                                    <td>
                                        <select type="dropdown" class="form-control" name="supplier_id[]" id="supplier_id" onchange="getFields(this);" autocomplete="off" required>
                                            <option value="0" @if($valueArray[$row]['supplier_id'] == ''){{'selected'}}@endif>Please Select a Model</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}" @if( $valueArray[$row]['supplier_id'] == $supplier->name){{'selected'}}@endif>{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                        @if(array_key_exists('supplier_id', $errorValues[$row]))<small class="text-danger text-capitalize">{{$errorValues[$row]['supplier_id']}}</small>@endif

                                    </td>
                                    <td>
                                        <select type="dropdown" class="form-control" name="manufacturer_id[]" id="manufacturer_id" onchange="getFields(this);" autocomplete="off" required>
                                            <option value="0" @if($valueArray[$row]['manufacturer_id'] == ''){{'selected'}}@endif>Please Select a Model</option>
                                            @foreach($manufacturers as $manufacturer)
                                                <option value="{{$manufacturer->id }}" @if( $valueArray[$row]['manufacturer_id'] == $manufacturer->name){{'selected'}}@endif>{{ $manufacturer->name }}</option>
                                            @endforeach
                                        </select>
                                        @if(array_key_exists('manufacturer_id', $errorValues[$row]))<small class="text-danger text-capitalize">{{$errorValues[$row]['manufacturer_id']}}</small>@endif

                                    </td>
                                    <td>
                                        <select type="dropdown" class="form-control" name="location_id[]" id="location_id" onchange="getFields(this);" autocomplete="off" required>
                                            <option value="0" @if($valueArray[$row]['location_id'] == ''){{'selected'}}@endif>Please Select a Model</option>
                                            @foreach($locations as $location)
                                                <option value="{{ $location->id  }}" @if( $valueArray[$row]['location_id'] == $location->name){{'selected'}}@endif>{{ $location->name }}</option>
                                            @endforeach
                                        </select>
                                        @if(array_key_exists('location_id', $errorValues[$row]))<small class="text-danger text-capitalize">{{$errorValues[$row]['location_id']}}</small>@endif

                                    </td>
                                    <td>
                                        <input type="text"
                                               class="form-control <?php if (in_array('order_no', $errors)) {?>border-danger<?php }?>
                                               {{--                                    <?php if ($errors->has('name')) {?>border-danger<?php }?>--}}
                                                   "
                                               name="order_no[]"
                                               id="order_no" placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['order_no'] }}" required>
                                        @if(array_key_exists('order_no', $errorValues[$row]))<small class="text-danger text-capitalize">{{$errorValues[$row]['order_no']}}</small>@endif

                                    </td>
                                    <td>
                                        <input type="text"
                                               class="form-control <?php if (in_array('serial_no', $errors)) {?>border-danger<?php }?>
                                               {{--                                    <?php if ($errors->has('name')) {?>border-danger<?php }?>--}}
                                                   "
                                               name="serial_no[]"
                                               id="serial_no" placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['serial_no'] }}" required>
                                        @if(array_key_exists('serial_no', $errorValues[$row]))<small class="text-danger text-capitalize">{{$errorValues[$row]['serial_no']}}</small>@endif

                                    </td>
                                    <td>
                                        <input type="text"
                                               class="form-control <?php if (in_array('purchased_cost', $errors)) {?>border-danger<?php }?>
                                               {{--                                    <?php if ($errors->has('name')) {?>border-danger<?php }?>--}}
                                                   "
                                               name="purchased_cost[]"
                                               id="purchased_cost" placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['purchased_cost'] }}" required>
                                        @if(array_key_exists('purchased_cost', $errorValues[$row]))<small class="text-danger text-capitalize">{{$errorValues[$row]['purchased_cost']}}</small>@endif

                                    </td><td>
                                        <input type="text"
                                               class="form-control <?php if (in_array('purchased_date', $errors)) {?>border-danger<?php }?>
                                               {{--                                    <?php if ($errors->has('name')) {?>border-danger<?php }?>--}}
                                                   "
                                               name="purchased_date[]"
                                               id="purchased_date" placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['purchased_date'] }}" required>
                                        @if(array_key_exists('purchased_date', $errorValues[$row]))<small class="text-danger text-capitalize">{{$errorValues[$row]['purchased_date']}}</small>@endif

                                    </td><td>
                                        <input type="text"
                                               class="form-control <?php if (in_array('warranty', $errors)) {?>border-danger<?php }?>
                                               {{--                                    <?php if ($errors->has('name')) {?>border-danger<?php }?>--}}
                                                   "
                                               name="warranty[]"
                                               id="warranty" placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['warranty'] }}" required>
                                        @if(array_key_exists('warranty', $errorValues[$row]))<small class="text-danger text-capitalize">{{$errorValues[$row]['warranty']}}</small>@endif

                                    </td>
                                    <td>
                                        <input type="text"
                                               class="form-control <?php if (in_array('notes', $errors)) {?>border-danger<?php }?>
                                               {{--                                    <?php if ($errors->has('name')) {?>border-danger<?php }?>--}}
                                                   "
                                               name="notes[]"
                                               id="notes" placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['notes'] }}" required>
                                        @if(array_key_exists('notes', $errorValues[$row]))<small class="text-danger text-capitalize">{{$errorValues[$row]['notes']}}</small>@endif

                                    </td>
                                </tr>
        @endforeach
{{--    </form>--}}
    </tbody>
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
                           <li>All Correct rows skip this page and import straight to the database so please don't re-import your file!</li>
                           <li>Struggling to Pass this stage are all your data fields in the correct format?</li>
                       </ol>
                    </div>
                    <div class="modal-footer">
                        <p>For Anymore information please email Apollo@clpt.co.uk</p>
                        <a href="https://clpt.sharepoint.com/:x:/s/WebDevelopmentTeam/ERgeo9FOFaRIvmBuTRVcvycBkiTnqHf3aowELiOt8Hoi1Q?e=CXfTdb" target="_blank" class="btn btn-info" >
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
    <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script>
        $('#import').click(function () {
            $('#manufacturer-id-test').val($(this).data('id'))
            //showModal
            $('#importManufacturerModal').modal('show')

        })

        $('.deleteBtn').click(function () {
            $('#deleteForm').attr('action', $(this).data('route'));
            //showModal
            $('#removeCategoryModal').modal('show');
        });

        $('#confirmBtn').click(function () {
            $('#deleteForm').submit();
        });

        $('.updateBtn').click(function () {
            var val = $(this).data('id');
            var name = $(this).data('name');
            var route = $(this).data('route');
            $('[name="name"]').val(name);
            $('#updateForm').attr('action', route);
            $('#updateCategoryModal').modal('show');
        });


        $(document).ready(function () {
            $('#categoryTable').DataTable({
                "columnDefs": [{
                    "targets": [0, 5],
                    "orderable": false,
                }],
                "order": [[1, "asc"]]
            });
        });
        //validation
            function checkErrors(obj){

            var token = $("[name='_token']").val();
            var data = new FormData();
            data.append('_token', token);

            //Names
            var inputs = $("input[name='name[]']").get();
            inputs.forEach(element => {
            data.append('name[]', element.value);
        });

            //status
            var stInputs = $("select[name='status_id[]']").get();
            stInputs.forEach(element => {
            data.append('status_id[]', element.value);
        });

            //Phone
            var supInputs = $("select[name='supplier_id[]']").get();
            supInputs.forEach(element => {
            data.append('supplier_id[]', element.value);
        });

            //Email
            var maInputs = $("select[name='manufacturer_id[]']").get();
            maInputs.forEach(element => {
            data.append('manufacturer_id[]', element.value);
        });

            var loInputs = $("select[name='location_id[]']").get();
            loInputs.forEach(element => {
            data.append('location_id[]', element.value);
        });

            var orInputs = $("input[name='order_no[]']").get();
            orInputs.forEach(element => {
            data.append('order_no[]', element.value);
        });

            var seInputs = $("input[name='serial_no[]']").get();
            seInputs.forEach(element => {
            data.append('serial_no[]', element.value);
        });

            var pcInputs = $("input[name='purchased_cost[]']").get();
            pcInputs.forEach(element => {
            data.append('purchased_cost[]', element.value);
        });

            var pdInputs = $("input[name='purchased_date[]']").get();
            pdInputs.forEach(element => {
            data.append('purchased_date[]', element.value);
        });

            var waInputs = $("input[name='warranty[]']").get();
            waInputs.forEach(element => {
            data.append('warranty[]', element.value);
        });

            var noInputs = $("input[name='notes[]']").get();
            noInputs.forEach(element => {
            data.append('notes[]', element.value);
        });

            $.ajax({
            url: '/components/create/ajax',
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: function(response){
            if(response === 'Success'){
            window.location.href = '/components';
        }else{
            $('small.text-danger').remove();
            $('input').removeClass('border-danger');
            var i = 0;
            Object.entries(response).forEach(entry => {
            const [key, value] = entry;
            res = key.split('.');
            const error = value.toString().replace(key, res[0]);
            $(`input[name='${res[0]}[]']:eq(${res[1]})`).addClass('border-danger');
            $(`input[name='${res[0]}[]']:eq(${res[1]})`).after(`<small class="text-danger text-capitalize">${error}</small>`);
            i++;
        });
            $('.alert.alert-danger').html(`There were ${i} errors in the following rows`);
        }
        },
        });
        }

    </script>
@endsection
