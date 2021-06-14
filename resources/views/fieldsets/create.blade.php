@extends('layouts.app')

@section('css')

@endsection

@section('content')
<form action="{{ route('fieldsets.store') }}" method="POST">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Fieldset</h1>

        <div>
            <a href="{{ route('fieldsets.index') }}"
                class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                    class="fas fa-chevron-left fa-sm text-white-50"></i> Back to Suppliers</a>
            <button type="submit" class="d-inline-block btn btn-sm btn-success shadow-sm"><i
                    class="far fa-save fa-sm text-white-50"></i> Save</button>
        </div>
    </div>

    <section class="">
        <p class="mb-4">Adding a new supplier to the asset management system. Enter in the following information and
            click the 'Save' button. Or click the 'Back' button
            to return the suppliers page.
        </p>
        <div class="row row-eq-height container m-auto">
            <div class="col-12 mb-4">
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
                            <input type="text"
                                class="form-control <?php if ($errors->has('name')) {?>border-danger<?php }?>"
                                name="name" id="name" placeholder="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card shadow h-100">
                    <div class="card-header">
                        <h5 class="text-right">Selected Fields</h5>
                    </div>
                    <div class="card-body text-right">
                        
                        <input type="text" id="fields" name="fields">
                        <div id="selected-fields">
                            <p>RAM <i class="fas fa-chevron-right"></i></p>
                            <p>Storage Type <i class="fas fa-chevron-right"></i></p>
                            <p>Storage <i class="fas fa-chevron-right"></i></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card shadow h-100">
                    <div class="card-header">
                        <h5>Field Options</h5>
                    </div>
                    <div class="card-body">
                        @foreach($fields as $field)
                            <p onclick="javascript:addField({{ $field->id}}, '{{$field->name}}')"><i class="fas fa-chevron-left"></i> {{ $field->name }}</p>
                        @endforeach
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
                <button type="button" class="btn btn-info" data-dismiss="modal" data-toggle="modal"
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
                    <button type='submit' class='btn btn-success' id='btn_upload'>Upload</button>
                </form>
            </div>

        </div>

    </div>
</div>
@endsection

@section('js')
<script>
    function addField(id, name){
        var string = 'document.getElementById('fields').value';
        var array = string.split(",");
        console.log(array.includes(id));

        if(array.includes(id)){

        }else{
            const p = document.createElement('p');
            p.innerHTML = name+' <i class="fas fa-chevron-right"></i>';
            document.getElementById('selected-fields').appendChild(p);
            document.getElementById('fields').value = id;
        }

    }
</script>
@endsection