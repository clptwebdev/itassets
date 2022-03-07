@extends('layouts.app')

@section('title', 'Edit '.$manufacturer->name)

@section('css')

@endsection

@section('content')
    <x-form.layout :action="route('manufacturers.update' , $manufacturer->id)" method="PATCH">
        <x-wrappers.nav title="Edit Manufacturer Details">
            @can('viewAny' , \App\Models\Manufacturer::class)
                <x-buttons.return :route="route('manufacturers.index')">Manufacturers</x-buttons.return>
            @endcan
            <x-buttons.help :route="route('documentation.index').'#collapseThirteenManufacturers'"/>
            <x-buttons.submit>Save</x-buttons.submit>
        </x-wrappers.nav>
        <section>
            <p class="mb-4">Below is the {{$manufacturer->name}} tile ,this is for the Manufacturer stored in the
                            management system.</p>
            <div class="row row-eq-height">
                <div class="col-12 col-md-8 col-lg-9 col-xl-10">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <x-form.errors/>
                            <div class="form-group">
                                <x-form.input name="name" :value="$manufacturer->name"/>
                            </div>
                            <div class="form-group">
                                <x-form.input name="supportPhone" :value="$manufacturer->supportPhone"/>
                            </div>
                            <div class="form-group">
                                <x-form.input title="Manufacturer Website" name="supportUrl"
                                              :value="$manufacturer->supportUrl"/>
                            </div>

                            <div class="form-group">
                                <x-form.input title="Email Address" name="supportEmail"
                                              :value="$manufacturer->supportEmail"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-3 col-xl-2">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <div class="w-100">
                                <div class="formgroup mb-2 p-2">
                                    <h4 class="h6 mb-3">Manufacturer</h4>
                                    <img id="profileImage" src="{{ asset('images/svg/manufacturer_image.svg') }}"
                                         width="100%" alt="Select Profile Picture" data-toggle="modal"
                                         data-target="#imgModal">
                                    <input type="hidden" id="photoId" name="photoId" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </x-form.layout>
@endsection

@section('modals')
    <!-- Profile Image Modal-->
    <div class="modal fade bd-example-modal-lg" id="imgModal" tabindex="-1" role="dialog"
         aria-labelledby="imgModalLabel" aria-hidden="true">
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
            document.getElementById("photoId").value = id;
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
                        document.getElementById("photoId").value = data.id;
                    }
                });
            });
        });
    </script>
@endsection
