@extends('layouts.app')

@section('title', 'Edit Component')

@section('css')

@endsection

@section('content')
    <form action="{{ route('components.update', $component->id) }}" method="POST">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Component</h1>

            <div>
                <a href="{{ route('components.index') }}"
                   class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                        class="fas fa-chevron-left fa-sm text-white-50"></i> Back to Components</a>
                <a href="{{ route('documentation.index')."#collapseNineComponent"}}"
                   class="d-none d-sm-inline-block btn btn-sm  bg-yellow shadow-sm"><i
                        class="fas fa-question fa-sm text-dark-50"></i>  need Help?</a>
                <button type="submit" class="d-inline-block btn btn-sm btn-green shadow-sm"><i
                        class="far fa-save fa-sm text-white-50"></i> Save
                </button>
            </div>
        </div>
        <x-form.errors/>
        <section>
            <p class="mb-4">Edit {{ $component->name}}, Component stored in the Apollo Asset Management System. Change the information
                and
                click the 'Save' button. Or click the 'Back' button
                to return the Components page.
            </p>
            <div class="row row-eq-height">
                <div class="col-12 col-md-8 col-lg-9">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <?php $name = $component->name;  ?>
                                <x-form.input name="name" formAttributes="required" :value="$name"/>
                            </div>
                            <div class="form-group">
                                <?php $serial_no = $component->serial_no;  ?>
                                <x-form.input name="serial_no" formAttributes="required" :value="$serial_no"/>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <?php $order_no = $component->order_no;  ?>
                                    <x-form.input name="order_no" formAttributes="required" :value="$order_no"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <?php $cost = $component->purchased_cost;  ?>
                                    <x-form.input name="purchased_cost" formAttributes="required" :value="$cost"/>
                                </div>
                                <div class="form-group col-md-4">
                                    <?php  $date =  \Carbon\Carbon::parse($component->purchased_date)->format('Y-m-d')?>
                                    <x-form.date name="purchased_date" formAttributes="required" :value="$date" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <?php $selected = $component->supplier->id; ?>
                                    <x-form.select name="supplier_id" formAttributes="required" :models="$suppliers" :selected="$selected"/>
                                </div>
                                <div class="form-group col-md-6">
                                    <?php $selected = $component->status->id; ?>
                                    <x-form.select name="status_id" formAttributes="required" :models="$statuses" :selected="$selected"/>
                                </div>
                            </div>
                            @php( $cat_array = [])
                            @foreach($component->category as $cc)
                            @php( $cat_array[] = $cc->id)
                            @endforeach
                            <div class="form-control h-auto p-4 mb-3 rounded">
                                <x-form.checkbox  :models="$categories" name="category" :checked="$cat_array"/>
                            </div>
                            <div class="form-group">
                                <?php $notes = $component->notes; ?>
                                <x-form.textarea  name="notes" formAttributes="rows='10'" :value="$notes"/>
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
                                    @if($component->photo()->exists())
                                        <img id="profileImage" src="{{ asset($component->photo->path) ?? asset('images/svg/components_image.svg')}}" width="100%" alt="Select Profile Picture" data-toggle="modal" data-target="#imgModal">
                                    @else
                                    <img id="profileImage"
                                         src="{{ asset('images/svg/components_image.svg') }}"
                                         width="100%"
                                         alt="Select Profile Picture" data-toggle="modal" data-target="#imgModal">
                                    @endif
                                    <input type="hidden" id="photo_id" name="photo_id" value="0">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group col-md-12">
                                <?php $selected = $component->location_id; ?>
                                <x-form.select name="location_id" formAttributes="required" :models="$locations" :selected="$selected"/>
                            </div>

                            <div class="form-group col-md-12">
                                <?php $warranty = $component->warranty  ?>
                                <x-form.input name="warranty"  :value="$warranty"/>
                            </div>
                            <div class="form-group col-md-12">
                                <?php $selected = $component->manufacturer->id; ?>
                                <x-form.select name="manufacturer_id" :models="$manufacturers" :selected="$selected"/>
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
                    <p>Select an image below:</p>
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
