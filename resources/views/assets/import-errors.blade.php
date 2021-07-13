@extends('layouts.app')

@section('css')
<link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4"><?php  ?>
        <h1 class="h3 mb-0 text-gray-800">Import

            Failures</h1>@php $errorRows = '';foreach($errorArray as $id => $key){ $errorRows = !empty($errorRows)? $errorRows.', '.$id:$id;}  @endphp
        <div class="alert alert-danger">You have several errors Within your Import in rows {{$errorRows}}</div>
        <div>
            <a href="/assets" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                    class="fas fa-plus fa-sm te
                        xt-white-50"></i> Back to assets</a>
            <button onclick="javscript:checkErrors(this);" class="d-inline-block btn btn-sm btn-success shadow-sm"><i
                    class="far fa-save fa-sm text-white-50"></i> Save
            </button>
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
                            <th>Asset Tag</th>
                            <th>Serial Num</th>
                            <th>Asset_model</th>
                            <th>Status Id</th>
                            <th>Purchased Date</th>
                            <th>Purchased Cost</th>
                            <th>Supplier</th>
                            <th>Order_no</th>
                            <th>Warranty</th>
                            <th>Location</th>
                            <th>Audit Date</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Asset Tag</th>
                            <th>Serial Num</th>
                            <th>Asset_model</th>
                            <th>Status Id</th>
                            <th>Purchased Date</th>
                            <th>Purchased Cost</th>
                            <th>Supplier</th>
                            <th>Order_no</th>
                            <th>Warranty</th>
                            <th>Location</th>
                            <th>Audit Date</th>
                        </tr>
                        </tfoot>

                        @csrf
                        @foreach($errorArray as $row =>$error)
                        <?php $errors = explode(",", $error); ?>
                        <tr>
                            <td>
                                <input type="number"  maxlength="11"
                                       class="form-control <?php if (in_array('name', $errors)) {?>border-danger<?php }?>
                                                   "
                                       name="asset_tag[]"
                                       id="asset_tag" value="{{ $valueArray[$row]['asset_tag'] }}"
                                       placeholder="This Row is Empty Please Fill!" required>
                                @if(array_key_exists('asset_tag', $errorValues[$row]))<small class="text-danger text-capitalize">{{$errorValues[$row]['asset_tag']}}</small>@endif
                            </td>
                            <td>
                                <input type="text"
                                       class="form-control <?php if (in_array('serial_no', $errors)) {?>border-danger<?php }?>
                                               {{--                                    <?php if ($errors->has('name')) {?>border-danger<?php }?>--}}
                                                   "
                                       name="serial_no[]"
                                       id="serial_no" value="{{ $valueArray[$row]['serial_no'] }}"
                                       placeholder="This Row is Empty Please Fill!" required>
                                @if(array_key_exists('serial_no', $errorValues[$row]))<small class="text-danger text-capitalize">{{$errorValues[$row]['serial_no']}}</small>@endif
                            </td>
                            <td>


                                <div class="form-group">
                                <select type="dropdown" class="form-control" name="asset_model[]" id="asset_model" onchange="getFields(this);" autocomplete="off" required>
                                    <option value="0" @if($valueArray[$row]['asset_model_id'] == ''){{'selected'}}@endif>Please Select a Model</option>
                                    @foreach($models as $model)
                                        <option value="{{ $model->id }}" @if( $valueArray[$row]['asset_model_id'] == $model->name){{'selected'}}@endif>{{ $model->name }}</option>
                                    @endforeach
                                </select>
                </div>
                            </td>
                            <td>
                                <select type="dropdown" class="form-control" name="status_id[]" id="status_id" onchange="getFields(this);" autocomplete="off" required>
                                    <option value="0" @if($valueArray[$row]['status_id'] == ''){{'selected'}}@endif>Please Select a Model</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $valueArray[$row]['status_id'] }}" @if( $valueArray[$row]['status_id'] == $status->name){{'selected'}}@endif>{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>

                                <input type="date"
                                       class="form-control <?php if (in_array('purchased_date', $errors)) {?>border-danger<?php }?>
                                               {{--                                    <?php if ($errors->has('name')) {?>border-danger<?php }?>--}}
                                                   "
                                       name="purchased_date[]"
                                       id="purchased_date" placeholder="This Row is Empty Please Fill!"
                                       value="{{ \Carbon\Carbon::parse(str_replace('/','-',$valueArray[$row]['purchased_date']))->format("Y-m-d")}}" required>
                                @if(array_key_exists('purchased_date', $errorValues[$row]))<small class="text-danger text-capitalize">{{$errorValues[$row]['purchased_date']}}</small>@endif

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

                            </td>
                            <td>
                                <select type="dropdown" class="form-control" name="supplier_id[]" id="supplier_id" onchange="getFields(this);" autocomplete="off" required>
                                    <option value="0" @if($valueArray[$row]['supplier_id'] == ''){{'selected'}}@endif>Please Select a Model</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $valueArray[$row]['supplier_id'] }}" @if( $valueArray[$row]['supplier_id'] == $supplier->name){{'selected'}}@endif>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
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
                                <select type="dropdown" class="form-control" name="location_id[]" id="location_id" onchange="getFields(this);" autocomplete="off" required>
                                    <option value="0" @if($valueArray[$row]['location_id'] == ''){{'selected'}}@endif>Please Select a Model</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $valueArray[$row]['location_id'] }}" @if( $valueArray[$row]['location_id'] == $location->name){{'selected'}}@endif>{{ $location->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="date"
                                       class="form-control <?php if (in_array('audit_date', $errors)) {?>border-danger<?php }?>
                                       {{--                                    <?php if ($errors->has('name')) {?>border-danger<?php }?>--}}
                                           "
                                       name="audit_date[]"
                                       id="audit_date" placeholder="This Row is Empty Please Fill!"
                                       value="{{ \Carbon\Carbon::parse(str_replace('/','-',$valueArray[$row]['audit_date']))->format("Y-m-d")}}" required>
                                @if(array_key_exists('audit_date', $errorValues[$row]))<small class="text-danger text-capitalize">{{$errorValues[$row]['audit_date']}}</small>@endif

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

@endsection

@section('js')
<script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
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
        var inputs = $("input[name='asset_tag[]']").get();
        inputs.forEach(element => {
            data.append('asset_tag[]', element.value);
        });

        //status
        var snInputs = $("input[name='serial_no[]']").get();
        snInputs.forEach(element => {
            data.append('serial_no[]', element.value);
        });

        //Phone
        var astInputs = $("select[name='asset_model[]']").get();
        astInputs.forEach(element => {
            data.append('asset_model[]', element.value);
        });

        //Email
        var maInputs = $("select[name='status_id[]']").get();
        maInputs.forEach(element => {
            data.append('status_id[]', element.value);
        });

        var pdInputs = $("input[name='purchased_date[]']").get();
        pdInputs.forEach(element => {
            data.append('purchased_date[]', element.value);
        });

        var pcInputs = $("input[name='purchased_cost[]']").get();
        pcInputs.forEach(element => {
            data.append('purchased_cost[]', element.value);
        });

        var supInputs = $("select[name='supplier_id[]']").get();
        supInputs.forEach(element => {
            data.append('supplier_id[]', element.value);
        });


        var odInputs = $("input[name='order_no[]']").get();
        odInputs.forEach(element => {
            data.append('order_no[]', element.value);
        });

        var waInputs = $("input[name='warranty[]']").get();
        waInputs.forEach(element => {
            data.append('warranty[]', element.value);
        });

        var loInputs = $("select[name='location_id[]']").get();
        loInputs.forEach(element => {
            data.append('location_id[]', element.value);
        });
        var adtInputs = $("input[name='audit_date[]']").get();
        adtInputs.forEach(element => {
            data.append('audit_date[]', element.value);
        });
        $.ajax({
            url: '/assets/create/ajax',
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: function(response){
                if(response === 'Success'){
                    window.location.href = '/assets';
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
