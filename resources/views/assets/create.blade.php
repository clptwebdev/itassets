@extends('layouts.app')

@section('css')

@endsection

@section('content')
    <form action="" method="POST">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Add New Asset</h1>

            <div>
                <a href="{{ route('assets.index')}}"
                   class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                        class="fas fa-chevron-left fa-sm text-white-50"></i> Back to Assets</a>
                <button type="submit" class="d-inline-block btn btn-sm btn-success shadow-sm"><i
                        class="far fa-save fa-sm text-white-50"></i> Save
                </button>
            </div>
        </div>

        <section>
            <p class="mb-4">Adding a new Asset to the asset management system. Enter in the following information and
                click
                the 'Save' button. Or click the 'Back' button
                to return the Assets page.
            </p>
            <div class="row row-eq-height container m-auto">
                <div class="col-12">
                    <div class="card shadow h-100">
                        <div class="card-body">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="asset_tag">Asset Tag Number</label><span class="text-danger">*</span>
                                    <input type="text"
                                           class="form-control <?php if ($errors->has('name')) {?>border-danger<?php }?>"
                                           name="asset_tag" id="asset_tag" placeholder="" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="asset_model">Asset Model Select</label><span
                                        class="text-danger">*</span>
                                    <select type="dropdown"
                                            class="form-control <?php if ($errors->has('name')) {?>border-danger<?php }?>"
                                            name="name" id="asset_model" placeholder="Ipad" required>
                                        <option>testing123</option>
                                    </select>
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="School Location">School Location</label><span
                                            class="text-danger">*</span>
                                        <select type="text"
                                                class="form-control mb-3 <?php if ($errors->has('school_location') || $errors->has('school_location')) {?>border-danger<?php }?>"
                                                name="school_location" id="school_location" required>
                                            <option value="0" selected>Please select a Location</option>
                                            @foreach($locations as $location)
                                                <option>{{$location->name}}</option>
                                            @endforeach
                                            {{--                                        value="{{ $location->id }}" @if($asset->location->id == $location->id) selected @endif--}}
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="manufacturer_location">Device Manufacturer</label><span
                                            class="text-danger">*</span>
                                        <select type="text"
                                                class="form-control mb-3 <?php if ($errors->has('manufacturer_location') || $errors->has('manufacturer_location')) {?>border-danger<?php }?>"
                                                name="manufacturer_location" id="manufacturer_location" required>
                                            <option value="0" selected>Please select a Manufacturer</option>
                                            @foreach($manufacturers as $manufacturer)
                                                <option>{{$manufacturer->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="purchased_date">Purchased Date</label>
                                        <input type="date"
                                               class="form-control <?php if ($errors->has('name')) {?>border-danger<?php }?>"
                                               name="purchased_date" id="purchased_date">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="purchased_cost">Purchased Cost</label>
                                        <input type="dropdown"
                                               class="form-control <?php if ($errors->has('name')) {?>border-danger<?php }?>"
                                               name="purchased_cost" id="purchased_cost" placeholder="£">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="status">Current Status</label><span class="text-danger">*</span>
                                        <select type="text"
                                                class="form-control mb-3 <?php if ($errors->has('status') || $errors->has('status')) {?>border-danger<?php }?>"
                                                name="status" id="status" value="Stored" required>
                                            <option>Stored</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endsection

    @section('modals')
        <!-- Profile Image Modal-->
            <div class="modal fade bd-example-modal-lg" id="imgModal" tabindex="-1" role="dialog"
                 aria-labelledby="imgModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary-blue text-white">
                            <h5 class="modal-title" id="imgModalLabel">Select Image</h5>
                            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Modal -->
            <div id="uploadModal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="imgUploadLabel">Upload Media</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Form -->
                            <form id="imageUpload">
                                Name: <input type="text" placeholder="Enter File Name" name="name" class="form-control">
                                Select file : <input type='file' name='file' id='file' class='form-control'><br>
                                <button type='submit' class='btn btn-success' id='btn_upload'>Upload</button>
                            </form>
                        </div>

                    </div>

                </div>
            </div>
        @endsection

        @section('js')
            <script>
                function selectPhoto(id, src) {
                    document.getElementById("profileImage").src = src;
                    document.getElementById("photo_id").value = id;
                    $('#imgModal').modal('hide');
                }

                $(document).ready(function () {
                    $("form#imageUpload").submit(function (e) {
                        e.preventDefault();
                        var formData = new FormData(this);
                        var urlto = '/photo/upload';
                        var route = '{{asset("/")}}';
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        // AJAX request
                        $.ajax({
                            url: urlto,
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (data) {
                                $('#uploadModal').modal('hide');
                                document.getElementById("profileImage").src = route + data.path;
                                document.getElementById("photo_id").value = data.id;
                            }
                        });
                    });
                });
            </script>
@endsection
