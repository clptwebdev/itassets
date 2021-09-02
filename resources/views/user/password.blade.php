@extends('layouts.app')

@section('title', 'Update Password')

@section('css')

@endsection

@section('content')
    <form action="{{ route('user.update')}}" method="POST">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Change your Password</h1>

            <div class="mt-4 mt-md-0">
                <a href="{{ route('users.index')}}"
                   class="d-inline-block btn btn-sm btn-secondary shadow-sm"><i
                        class="fas fa-chevron-left fa-sm text-white-50"></i> Back to Users</a>
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
            <div class="row row-eq-height auto-width m-auto">
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

                            <h3 class="h6 text-center mb-3">User Information</h3>

                            <div class="formgroup mb-2 p-2">
                                <div class="m-auto p-2 border border-secondary" style="max-width: 250px;">
                                    @php
                                    if(auth()->user()->photo()->exists()){
                                        $path = auth()->user()->photo->path;
                                    }else{
                                        $path = 'images/profile.png';
                                    }
                                    @endphp
                                    <img id="profileImage" src="{{ asset($path)}}" width="100%"
                                    alt="Select Profile Picture" data-toggle="modal" data-target="#imgModal">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name">Name</label><span class="text-danger">*</span>
                                <input type="text" class="form-control <?php if ($errors->has('name')) {?>border-danger<?php }?>" name="name"
                                    id="name" placeholder="" value="{{ old('name') ?? auth()->user()->name}}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="old_password">Old Password</label><span class="text-danger">*</span>
                                <input type="password" class="form-control <?php if ($errors->has('old_password')) {?>border-danger<?php }?>" name="old_password"
                                    id="old_password" placeholder="" value="">
                            </div>

                            <div class="form-group">
                                <label for="new_password">New Password</label><span class="text-danger">*</span>
                                <input type="password" class="form-control <?php if ($errors->has('old_password')) {?>border-danger<?php }?>" name="new_password"
                                    id="new_password" placeholder="" value="">
                            </div>

                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label><span class="text-danger">*</span>
                                <input type="password" class="form-control <?php if ($errors->has('confirm_password')) {?>border-danger<?php }?>" name="confirm_password"
                                    id="confirm_password" placeholder="" value="">
                            </div>

                        </div>
                    </div>                           
                </div>
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
