@extends('layouts.app')

@section('title', 'Edit Supplier')

@section('css')

@endsection

@section('content')
<form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Supplier</h1>

        <div>
            <a href="{{ route('suppliers.index') }}"
                class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                    class="fas fa-chevron-left fa-sm text-white-50"></i> Back to Suppliers</a>
            <button type="submit" class="d-inline-block btn btn-sm btn-green shadow-sm"><i
                    class="far fa-save fa-sm text-white-50"></i> Save</button>
        </div>
    </div>

    <section>
        <p class="mb-4">Change the following information for the selected supplier and click the 'Save' button to update or click 'Back' to return to all suppliers.</p>
        <div class="row row-eq-height">
            <div class="col-12 col-md-8 col-lg-9">
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
                        @method('PATCH')

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text"
                                class="form-control <?php if ($errors->has('name')) {?>border-danger<?php }?>"
                                name="name" id="name" placeholder="" value="{{ $supplier->name}}" required>
                        </div>
                        <div class="form-group">
                            <label for="address2">Street Address</label>
                            <input type="text"
                                class="form-control mb-3 <?php if ($errors->has('address_1') || $errors->has('address_2')) {?>border-danger<?php }?>"
                                name="address_1" id="address_1" value="{{ $supplier->address_1}}">
                            <input type="text" class="form-control" name="address_2" id="address_2" value="{{ $supplier->address_2}}">
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="city">City</label>
                                <input type="text"
                                    class="form-control <?php if ($errors->has('city')) {?>border-danger<?php }?>"
                                    id="city" name="city" value="{{ $supplier->city}}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="city">County</label>
                                <input type="text"
                                    class="form-control <?php if ($errors->has('county')) {?>border-danger<?php }?>"
                                    id="county" name="county" value="{{ $supplier->county}}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="postcode">Post Code</label>
                                <input type="text"
                                    class="form-control <?php if ($errors->has('postcode')) {?>border-danger<?php }?>"
                                    id="postcode" name="postcode" value="{{ $supplier->postcode}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="10">{{ $supplier->notes }}</textarea>
                        </div>



                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-3">
                <div class="card shadow h-100">
                    <div class="card-body">
                        <div class="w-100">
                            <div class="formgroup mb-2 p-2">
                                <h4 class="h6 mb-3">Location Image</h4>
                                <img id="profileImage" src="@if($supplier->photo_id != 0) {{ asset($supplier->photo->path) }} @else {{ asset('images/svg/suppliers.svg')}} @endif" width="100%"
                                    alt="Select Profile Picture" data-toggle="modal" data-target="#imgModal">
                                <input type="hidden" id="photo_id" name="photo_id" value="0">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="url">Website URL:</label>
                            <input class="form-control" type="text" id="url" name="url" placeholder="https://" value="{{ $supplier->url}}" required>
                        </div>

                        <div class="form-group">
                            <label for="telephone">Telephone</label>
                            <input type="text" class="form-control" name="telephone" id="telephone" value="{{ $supplier->telephone }}">
                        </div>

                        <div class="form-group">
                            <label for="telephone">Fax</label>
                            <input type="text" class="form-control" name="fax" id="fax" value="{{ $supplier->fax ?? ''}}">
                        </div>

                        <div class="form-group">
                            <label for="telephone">Email Address</label>
                            <input type="text" class="form-control" name="email" id="email" placeholder="@" required value="{{ $supplier->email }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-admin.suppliers.details />

    </section>
</form>
@endsection

@section('modals')
<!-- Profile Image Modal-->
<div class="modal fade bd-example-modal-lg" id="imgModal" tabindex="-1" role="dialog" aria-labelledby="imgModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary-blue text-white">
                <h5 class="modal-title" id="imgModalLabel">Select Image</h5>
                <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Select an image below:.</p>
                <?php $photos = App\Models\Photo::all();?>
                <img src="{{ asset('images/svg/location-image.svg') }}" width="80px" alt="Default Picture"
                    onclick="selectPhoto(0, '{{ asset('images/svg/location-image.svg') }}');">
                @foreach($photos as $photo)
                <img src="{{ asset($photo->path) }}" width="80px" alt="{{ $photo->name }}"
                    onclick="selectPhoto('{{ $photo->id }}', '{{ asset($photo->path) }}');">
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-blue" data-dismiss="modal" data-toggle="modal"
                    data-target="#uploadModal">Upload
                    file</button>
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
                    <button type='submit' class='btn btn-green' id='btn_upload'>Upload</button>
                </form>
            </div>

        </div>

    </div>
</div>
@endsection

@section('js')
<script>
    function selectPhoto(id, src){
            document.getElementById("profileImage").src = src;
            document.getElementById("photo_id").value = id;
            $('#imgModal').modal('hide');
        }

        $(document).ready(function(){
           $("form#imageUpload").submit(function(e) {
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
                    success: function(data) {
                        $('#uploadModal').modal('hide');
                        document.getElementById("profileImage").src = route+data.path;
                        document.getElementById("photo_id").value = data.id;
                    }
                });
            });
        });
</script>
@endsection