@extends('layouts.app')

@section('title', 'Edit Accessory')

@section('css')

@endsection

@section('content')
    <form action="{{ route('accessories.update', $accessory->id) }}" method="POST">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Accessory</h1>

            <div>
                <a href="{{ route('accessories.index') }}"
                   class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
                        class="fas fa-chevron-left fa-sm text-dark-50"></i> Back to Accessories</a>
                <button type="submit" class="d-inline-block btn btn-sm btn-green shadow-sm"><i
                        class="far fa-save fa-sm text-white-50"></i> Save
                </button>
            </div>
        </div>

        <section>
            <p class="mb-4">Edit {{ $accessory->name}} and change any of the following information. Click the 'Save' button. Or click the 'Back' button
                to return the Accessories page.
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
                                {{ method_field('PATCH') }}

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text"
                                       class="form-control <?php if ($errors->has('name')) {?>border-danger<?php }?>"
                                       name="name" id="name" placeholder="accessory Name" value="{{$accessory->name}}">
                            </div>
                            <div class="form-group">
                                <label for="serial_no">Serial_no</label>
                                <input type="text"
                                       class="form-control mb-3 <?php if ($errors->has('serial_no')){?>border-danger<?php }?>"
                                       name="serial_no" id="serial_no" value="{{$accessory->serial_no}}">
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="order_no">Order_no</label>
                                    <input type="text"
                                           class="form-control <?php if ($errors->has('order_no')) {?>border-danger<?php }?>"
                                           id="order_no" name="order_no"  value="{{$accessory->order_no}}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="purchased_cost">Purchased Cost</label>
                                    <input type="text"
                                           class="form-control <?php if ($errors->has('purchase_cost')) {?>border-danger<?php }?>"
                                           id="purchased_cost" name="purchased_cost" value="{{$accessory->purchased_cost}}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="purchased_date">Purchased Date</label>
                                    <input type="date"
                                           class="form-control <?php if ($errors->has('purchased_date')) {?>border-danger<?php }?>"
                                           id="purchased_date" name="purchased_date" value="{{ \Carbon\Carbon::parse($accessory->purchased_date)->format('Y-m-d')}}" required>
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">

                                    <label for="suppliers">Supplier</label>
                                    <select type="text"
                                            class="form-control <?php if ($errors->has('supplier_id')) {?>border-danger<?php }?>"
                                            id="supplier_id" name="supplier_id" required>
                                        <option value="0" @if(old('supplier_id') == 0){{'selected'}}@endif >No
                                            Supplier
                                        </option>
                                        @foreach($suppliers as $supplier)
                                            <option
                                                value="{{ $supplier->id }}" @isset($accessory->supplier->id)@if($accessory->supplier->id == $supplier->id){{'selected'}}@endif @endisset>{{ $supplier->name}}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group col-md-6">
                                    <label for="status">Status</label>
                                    <select
                                        class="form-control <?php if ($errors->has('status_id')) {?>border-danger<?php }?>"
                                        id="status_id" name="status_id">
                                        <option value="0" @if(old('status_id') == 0){{'selected'}}@endif>Unset
                                        </option>
                                        @foreach($statuses as $status)
                                            <option
                                                value="{{ $status->id }}"@isset($accessory->status->id) @if($accessory->status->id == $status->id){{'selected'}}@endif @endisset>{{ $status->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @php( $cat_array = [])
                            @foreach($accessory->category as $cc)
                            @php( $cat_array[] = $cc->id)
                            
                            @endforeach
                            <div id="categories" class="form-control h-100 p-4 mb-3">
                                <h4 class="h6 mb-4 text-center">Categories</h4>
                                @foreach($categories as $category)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="{{ $category->id }}" name="category[]" id="category{{$category->id}}" @if(in_array($category->id, $cat_array)){{ 'checked'}}@endif>
                                    <label class="form-check-label" for="category{{$category->id}}">{{ $category->name }}</label>
                                </div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea name="notes" id="notes" class="form-control" rows="10">{{$accessory->notes}}</textarea>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-3">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <div class="w-100">
                                <div class="formgroup mb-2 p-2">
                                    <h4 class="h6 mb-3">Image</h4>
                                    @if($accessory->photo()->exists())
                                        <img id="profileImage" src="{{ asset($accessory->photo->path) ?? asset('images/svg/accessory_image.svg')}}" width="100%" alt="Select Profile Picture" data-toggle="modal" data-target="#imgModal"> 
                                    @else
                                    <img id="profileImage"
                                         src="{{ asset('images/svg/accessory_image.svg') }}"
                                         width="100%"
                                         alt="Select Profile Picture" data-toggle="modal" data-target="#imgModal">
                                    @endif
                                    <input type="hidden" id="photo_id" name="photo_id" value="0">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group col-md-12">
                                <label for=" school location">Location</label>
                                <select
                                    class="form-control <?php if ($errors->has('location_id')) {?>border-danger<?php }?>"
                                    id="location_id" name="location_id" required>
                                    <option value="0" @if(old('location_id') == 0){{'selected'}}@endif>Unallocated
                                    </option>
                                    @foreach($locations as $location)
                                        <option
                                            value="{{ $location->id }}" @if($accessory->location->id == $location->id){{'selected'}}@endif>{{ $location->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="warranty">Warranty</label>
                                <input type="text"
                                       class="form-control <?php if ($errors->has('warranty')) {?>border-danger<?php }?>"
                                       id="warranty" name="warranty" value="{{$accessory->warranty}}">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="Warranty">Manufacturer</label>
                                <select
                                    class="form-control <?php if ($errors->has('manufacturer')) {?>border-danger<?php }?>"
                                    id="manufacturer_id" name="manufacturer_id">
                                    <option value="0" @if(old('manufacturer_id') == 0){{'selected'}}@endif>Unallocated
                                    </option>
                                    @foreach($manufacturers as $manufacturer)
                                        <option
                                            value="{{$manufacturer->id}}" @isset($accessory->manufacturer->id)@if($accessory->manufacturer->id == $manufacturer->id){{'selected'}}@endif @endisset>{{$manufacturer->name}}</option>
                                    @endforeach
                                </select>
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

