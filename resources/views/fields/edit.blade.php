@extends('layouts.app')

@section('css')

@endsection

@section('content')
<form action="{{ route('fields.update', $field) }}" method="POST">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Custom Field</h1>

        <div>
            <a href="{{ route('fields.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                    class="fas fa-chevron-left fa-sm text-white-50"></i> Back to All Fields</a>
            <button type="submit" class="d-inline-block btn btn-sm btn-success shadow-sm"><i
                    class="far fa-save fa-sm text-white-50"></i> Save</button>
        </div>
    </div>

    <section>
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
                        @method('PATCH')

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text"
                                class="form-control <?php if ($errors->has('name')) {?>border-danger<?php }?>"
                                name="name" id="name" value="{{ $field->name }}">
                        </div>
                        <div class="form-group">
                            <label for="type">Field Type</label>
                            <select name="type" class="form-control" onchange="javascript:sortFormat(this);">
                                <option value="text" <?php if($field->type == 'Text') {?>selected="selected"<?php }?>>Text</option>
                                <option value="textarea" <?php if($field->type == 'Textbox') {?>selected="selected"<?php }?>>Multi-Line Text</option>
                                <option value="select" <?php if($field->type == 'Select') {?>selected="selected"<?php }?>>Select</option>
                                <option value="checkbox" <?php if($field->type == 'Checkbox') {?>selected="selected"<?php }?>>Checkbox</option>
                            </select>
                        </div>

                        <div id="format-div" class="form-group">
                            <label for="format">Format</label>
                            <select name="format" class="form-control">
                                <option value="alpha" @if($field->format == 'Alpha') {{'selected'}} @endif>Aplha</option>
                                <option value="alpha-num" @if($field->format == 'Alpha-num') {{ 'selected'}} @endif>Alpha-Numeric</option>
                                <option value="num" @if($field->format == 'Num') {{'selected'}} @endif>Numeric</option>
                                <option value="date" @if($field->format == 'Date') {{'selected'}} @endif>Date</option>
                                <option value="url" @if($field->format == 'Url') {{'selected'}} @endif>URL</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <p>Is this field required?</p>
                            <input type="radio" id="field_required_yes" name="required" value="1" @if($field->requried == 1){{'checked'}}@endif>
                            <label for="field_required_yes">Yes</label><br>
                            <input type="radio" id="field_required_no" name="required" value="0" @if($field->requried == 0){{'checked'}}@endif>
                            <label for="field_required_no">No</label>
                        </div>

                        <div id="value-div" class="form-group" <?php if($field->type != 'Checkbox'){?>style="display: none;"<?php }?>>
                            <label for="value">Values</label>
                            <textarea name="value" id="value" cols="30" rows="10" class="form-control">{{ $field->value }}</textarea>
                            <small>Enter one per line</small>
                        </div>

                        <div class="form-group">
                            <label for="help">Help Text</label>
                            <input type="text" name="help" class="form-control" value="{{ $field->help }}">
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