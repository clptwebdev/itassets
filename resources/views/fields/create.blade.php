@extends('layouts.app')

@section('title', 'Create New Asset Field')

@section('css')

@endsection

@section('content')
<form action="{{ route('fields.store') }}" method="POST">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Field</h1>

        <div>
            <a href="{{ route('fields.index') }}"
                class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                    class="fas fa-chevron-left fa-sm text-white-50"></i> Back to All Fields</a>
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

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text"
                                class="form-control <?php if ($errors->has('name')) {?>border-danger<?php }?>"
                                name="name" id="name" placeholder="">
                        </div>

                        <div class="form-group">
                            <label for="type">Field Type</label>
                            <select name="type" class="form-control" onchange="javascript:sortFormat(this);">
                                <option value="text" selected>Text</option>
                                <option value="textarea">Multi-Line Text</option>
                                <option value="select">Select</option>
                                <option value="checkbox">Checkbox</option>
                            </select>
                        </div>

                        <div id="format-div" class="form-group">
                            <label for="format">Format</label>
                            <select name="format" class="form-control">
                                <option value="alpha">Aplha</option>
                                <option value="alpha-num">Alpha-Numeric</option>
                                <option value="num">Numeric</option>
                                <option value="date">Date</option>
                                <option value="url">URL</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <p>Is this field required?</p>
                            <input type="radio" id="field_required_yes" name="required" value="1">
                            <label for="field_required_yes">Yes</label><br>
                            <input type="radio" id="field_required_no" name="required" value="0">
                            <label for="field_required_no">No</label>
                        </div>
                        
                        <div id="value-div" class="form-group" style="display: none;">
                            <label for="value">Values</label>
                            <textarea name="value" id="value" cols="30" rows="10" class="form-control"></textarea>
                            <small>Enter one per line</small>
                        </div>

                        <div class="form-group">
                            <label for="help">Help Text</label>
                            <input type="text" name="help" class="form-control">
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

@endsection

@section('js')

<script type="text/javascript">
    function sortFormat(obj){
        if(obj.value != 'text'){ document.getElementById('format-div').style.display = "none";}else{document.getElementById('format-div').style.display = "block";}
        if(obj.value == 'checkbox' || obj.value == 'select'){ 
            document.getElementById('value-div').style.display ="block";
        }else{
            document.getElementById('value-div').style.display = "none";
        }
    }
</script>

@endsection