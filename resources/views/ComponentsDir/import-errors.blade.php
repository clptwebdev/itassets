@extends('layouts.app')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')
    <form action="/components/create/import" method="POST">

        <div class="d-sm-flex align-items-center justify-content-between mb-4"><?php  ?>
            <h1 class="h3 mb-0 text-gray-800">Import
                Failures</h1>@php $errorRows = '';foreach($errorArray as $id => $key){ $errorRows = !empty($errorRows)? $errorRows.', '.$id:$id;}  @endphp
            <div class="alert alert-danger">You have several errors Within your Import in rows {{$errorRows}}</div>
            <div>
                <a href="/manufacturers" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                        class="fas fa-plus fa-sm te
                        xt-white-50"></i> Back to Manufacturers</a>
                <button type="submit" class="d-inline-block btn btn-sm btn-success shadow-sm"><i
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
                                    </td>
                                    <td>
                                        <input type="text"
                                               class="form-control <?php if (in_array('status_id', $errors)) {?>border-danger<?php }?>
                                               {{--                                    <?php if ($errors->has('name')) {?>border-danger<?php }?>--}}
                                                   "
                                               name="status_id[]"
                                               id="supportUrl" value="{{ $valueArray[$row]['status_id'] }}"
                                               placeholder="This Row is Empty Please Fill!" required>
                                    </td>
                                    <td>
                                        <input type="text"
                                               class="form-control <?php if (in_array('supplier_id', $errors)) {?>border-danger<?php }?>
                                               {{--                                    <?php if ($errors->has('name')) {?>border-danger<?php }?>--}}
                                                   "
                                               name="supplier_id[]"
                                               id="supplier_id" placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['supplier_id'] }}" required>
                                    </td>
                                    <td>
                                        <input type="text"
                                               class="form-control <?php if (in_array('manufacturer_id', $errors)) {?>border-danger<?php }?>
                                               {{--                                    <?php if ($errors->has('name')) {?>border-danger<?php }?>--}}
                                                   "
                                               name="manufacturer_id[]"
                                               id="manufacturer_id" placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['manufacturer_id'] }}" required>
                                    </td>
                                    <td>
                                        <input type="text"
                                               class="form-control <?php if (in_array('location_id', $errors)) {?>border-danger<?php }?>
                                               {{--                                    <?php if ($errors->has('name')) {?>border-danger<?php }?>--}}
                                                   "
                                               name="location_id[]"
                                               id="location_id" placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['location_id'] }}" required>
                                    </td>
                                    <td>
                                        <input type="text"
                                               class="form-control <?php if (in_array('order_no', $errors)) {?>border-danger<?php }?>
                                               {{--                                    <?php if ($errors->has('name')) {?>border-danger<?php }?>--}}
                                                   "
                                               name="order_no[]"
                                               id="order_no" placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['order_no'] }}" required>
                                    </td>
                                    <td>
                                        <input type="text"
                                               class="form-control <?php if (in_array('serial_no', $errors)) {?>border-danger<?php }?>
                                               {{--                                    <?php if ($errors->has('name')) {?>border-danger<?php }?>--}}
                                                   "
                                               name="serial_no[]"
                                               id="serial_no" placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['serial_no'] }}" required>
                                    </td>
                                    <td>
                                        <input type="text"
                                               class="form-control <?php if (in_array('purchased_cost', $errors)) {?>border-danger<?php }?>
                                               {{--                                    <?php if ($errors->has('name')) {?>border-danger<?php }?>--}}
                                                   "
                                               name="purchased_cost[]"
                                               id="purchased_cost" placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['purchased_cost'] }}" required>
                                    </td><td>
                                        <input type="text"
                                               class="form-control <?php if (in_array('purchased_date', $errors)) {?>border-danger<?php }?>
                                               {{--                                    <?php if ($errors->has('name')) {?>border-danger<?php }?>--}}
                                                   "
                                               name="purchased_date[]"
                                               id="purchased_date" placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['purchased_date'] }}" required>
                                    </td><td>
                                        <input type="text"
                                               class="form-control <?php if (in_array('warranty', $errors)) {?>border-danger<?php }?>
                                               {{--                                    <?php if ($errors->has('name')) {?>border-danger<?php }?>--}}
                                                   "
                                               name="warranty[]"
                                               id="warranty" placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['warranty'] }}" required>
                                    </td>
                                    <td>
                                        <input type="text"
                                               class="form-control <?php if (in_array('notes', $errors)) {?>border-danger<?php }?>
                                               {{--                                    <?php if ($errors->has('name')) {?>border-danger<?php }?>--}}
                                                   "
                                               name="notes[]"
                                               id="notes" placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['notes'] }}" required>
                                    </td>
                                </tr>
        @endforeach
    </form>
    </tbody>
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
    </script>
@endsection
