@extends('layouts.app')

@section('css')

@endsection

@section('content')
<form action="{{ route('location.store') }}" method="POST">
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Add New Location</h1>

    <div>
        <a href="{{ route('location.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                class="fas fa-chevron-left fa-sm text-white-50"></i> Back to Locations</a>
        <button type="submit" class="d-inline-block btn btn-sm btn-success shadow-sm"><i
                class="far fa-save fa-sm text-white-50"></i> Save</button>
    </div>
</div>

<section>
    <p class="mb-4">Below are different tiles, one for each location stored in the management system. Each tile has different options and locations can created, updated, and deleted.</p>
    <div class="row row-eq-height">
        <div class="col-12 col-md-8 col-lg-9 col-xl-10">
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

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control <?php if ($errors->has('name')) {?>border-danger<?php }?>" name="name"
                            id="name" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="address2">Street Address</label>
                        <input type="text" class="form-control mb-3 <?php if ($errors->has('address_1') || $errors->has('address_2')) {?>border-danger<?php }?>" name="address_1"
                            id="address_1" placeholder="Street Name" required>
                        <input type="text" class="form-control" name="address_2" id="address_2" placeholder="Location">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="city">City</label>
                            <input type="text" class="form-control <?php if ($errors->has('city')) {?>border-danger<?php }?>" id="city"
                                name="city" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="city">County</label>
                            <input type="text" class="form-control <?php if ($errors->has('county')) {?>border-danger<?php }?>" id="county"
                                name="county" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="postcode">Zip</label>
                            <input type="text" class="form-control <?php if ($errors->has('postcode')) {?>border-danger<?php }?>"
                                id="postcode" name="postcode" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="telephone">Telephone</label>
                        <input type="text" class="form-control" name="telephone" id="telephone" placeholder="Telelphone">
                    </div>

                    <div class="form-group">
                        <label for="telephone">Email Address</label>
                        <input type="text" class="form-control" name="email" id="email" placeholder="Email">
                    </div>

                </div>
            </div>
        </div>

        <div class="col-12 col-md-4 col-lg-3 col-xl-2">
            <div class="card shadow h-100">
                <div class="card-body">
                    <div class="w-100">
                        <div class="formgroup mb-2 p-2">
                            <h4 class="h6 mb-3">Location Image</h4>
                            <img id="profileImage" src="{{ asset('images/svg/location-image.svg') }}" width="100%"
                                alt="Select Profile Picture" data-toggle="modal" data-target="#imgModal">
                            <input type="hidden" id="photo_id" name="photo_id" value="0">
                        </div>
                    </div>
                    <hr>
                    <label for="icon">Select School Icon Colour:</label>
                    <input type="color" id="icon" name="icon" value="#ff0000">
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mt-4">
        <div class="card-header bg-primary-blue text-white">Information</div>
        <div class="card-body"><p>There are currently <?php $locationAmmount =\App\Models\Asset::all()->count() ?>{{$locationAmmount ?? 0}} Locations on the System</p></div>

    </div>
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
                    <button type="button" class="btn btn-info" data-dismiss="modal" data-toggle="modal" data-target="#uploadModal">Upload
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
                        <button type='submit' class='btn btn-success' id='btn_upload'>Upload</button>
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
