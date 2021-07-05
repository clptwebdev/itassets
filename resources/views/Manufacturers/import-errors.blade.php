@extends('layouts.app')

@section('css')
    <link href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet"/>
@endsection

@section('content')
    <form action="/manufacturers/create/import" method="POST">

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
                            @foreach($errorArray as $row =>$error)
                                <?php $errors = explode(",", $error); ?>
                                <tr>
                                    <td>
                                        <input type="text"
                                               class="form-control <?php if (in_array('name', $errors)) {?>border-danger<?php }?>
                                                   "
                                               name="name[]"
                                               id="name" value="{{ $valueArray[$row]['name'] }}"
                                               placeholder="This Row is Empty Please Fill!" required></td>
                                    <td>
                                        <input type="text"
                                               class="form-control <?php if (in_array('supporturl', $errors)) {?>border-danger<?php }?>
                                               {{--                                    <?php if ($errors->has('name')) {?>border-danger<?php }?>--}}
                                                   "
                                               name="supportUrl[]"
                                               id="supportUrl" value="{{ $valueArray[$row]['supporturl'] }}"
                                               placeholder="This Row is Empty Please Fill!" required>
                                    </td>
                                    <td>
                                        <input type="text"
                                               class="form-control <?php if (in_array('supportphone', $errors)) {?>border-danger<?php }?>
                                               {{--                                    <?php if ($errors->has('name')) {?>border-danger<?php }?>--}}
                                                   "
                                               name="supportPhone[]"
                                               id="supportPhone" placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['supportphone'] }}" required>
                                    </td>
                                    <td>
                                        <input type="text"
                                               class="form-control <?php if (in_array('supportemail', $errors)) {?>border-danger<?php }?>
                                               {{--                                    <?php if ($errors->has('name')) {?>border-danger<?php }?>--}}
                                                   "
                                               name="supportEmail[]"
                                               id="supportEmail" placeholder="This Row is Empty Please Fill!"
                                               value="{{ $valueArray[$row]['supportemail'] }}" required>
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
