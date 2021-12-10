@extends('layouts.app')

@section('title', 'New Component')

@section('css')

@endsection

@section('content')
    <form action="{{ route('components.store') }}" method="POST">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Add New Component</h1>

            <div>
                <a href="{{ route('components.index') }}"
                   class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                        class="fas fa-chevron-left fa-sm text-white-50"></i> Back to Components</a>
                <button type="submit" class="d-inline-block btn btn-sm btn-green shadow-sm"><i
                        class="far fa-save fa-sm text-white-50"></i> Save
                </button>
            </div>
        </div>

        <section>
            <p class="mb-4">Adding a new Component to the asset management system. Enter in the following information
                and
                click the 'Save' button. Or click the 'Back' button
                to return the Components page.
            </p>
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
                            <div class="form-group">
                                <x-form.input name="name" formAttributes="required"/>
                            </div>
                            <div class="form-group">
                                <x-form.input name="serial_no" formAttributes="required"/>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <x-form.input name="order_no" formAttributes="required"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <x-form.input name="purchased_cost" formAttributes="required"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <x-form.date name="purchased_date" formAttributes="required "/>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <x-form.select name="supplier_id" formAttributes="required" :models="$suppliers"/>
                                </div>
                                <div class="form-group col-md-6">
                                    <x-form.select name="status_id" formAttributes="required" :models="$statuses"/>
                                </div>
                            </div>
                            <div class="form-control h-auto p-4 mb-3 rounded">
                                <x-form.checkbox name="category" :models="$categories" formAttributes=""/>
                            </div>
                            <div class="form-group">
                                <x-form.textarea name="notes" formAttributes="rows='10'"/>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <div class="w-100">
                                <div class="formgroup mb-2 p-2">
                                    <h4 class="h6 mb-3">Component Image</h4>
                                    <img id="profileImage"
                                         src="{{ asset('images/svg/components_image.svg') }}"
                                         width="100%"
                                         alt="Select Profile Picture" data-toggle="modal" data-target="#imgModal">
                                    <input type="hidden" id="photo_id" name="photo_id" value="0">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group col-md-12">
                                <x-form.select formAttributes="required" name="location_id" :models="$locations"/>
                            </div>
                            <div class="form-group col-md-12">
                                <x-form.input name="warranty"/>
                            </div>
                            <div class="form-group col-md-12">
                                <x-form.select name="manufacturer_id" :models="$manufacturers"/>
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
                        file
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
                        <button type='submit' class='btn btn-green' id='btn_upload'>Upload</button>
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
