@extends('layouts.app')

@section('title', 'Create Asset Model Fieldset')

@section('css')

@endsection

@section('content')
<form action="{{ route('fieldsets.store') }}" method="POST">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Fieldset</h1>

        <div>
            <a href="{{ route('fieldsets.index') }}"
                class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"><i
                    class="fas fa-chevron-left fa-sm text-white-50"></i> Back to Fieldsets</a>
            <button type="submit" class="d-inline-block btn btn-sm btn-success shadow-sm"><i
                    class="far fa-save fa-sm text-white-50"></i> Save</button>
        </div>
    </div>

    <section></section>
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
                        
                        <input type="hidden" id="fields" name="fields">
                        <div id="selected-fields">
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
                            <div id="select{{$field->id}}" class="p-2 clickable" onclick="javascript:addField({{ $field->id}}, '{{$field->name}}')"><i class="fas fa-chevron-left"></i> {{ $field->name }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>
@endsection

@section('modals')

@endsection

@section('js')
<script type="text/javascript">

    function addField(id, name){
        var string = document.getElementById('fields').value;
        var array = string.split(",");

        if(!array.includes(String(id))){
            //Create a New DIV element
            const div = document.createElement('div');
            div.setAttribute('id', 'selected'+id);
            div.className ='p-2 clickable';
            div.setAttribute('onclick', `removeField(${id},'${name}')`);
            div.innerHTML = name+' <i class="fas fa-chevron-right"></i>';
            //Add the DIV element to the Selected Fields
            document.getElementById('selected-fields').appendChild(div);
            //Add the ID to the Array stored in 'fields'
            if(document.getElementById('fields').value != ""){
               document.getElementById('fields').value += ','+parseInt(id);
            }else{
                document.getElementById('fields').value = parseInt(id);
            }
            //Remove the selected Name from the Select fields
            document.getElementById('select'+id).style.display = 'none';
        }
    }

    function removeField(id, name){
        obj = document.getElementById('selected'+id).remove();
        document.getElementById('select'+id).style.display = 'block';
        var string = document.getElementById('fields').value;
        var array = string.split(",");
        const index = array.indexOf(String(id));
        if (index > -1) {
            array.splice(index, 1);
        }
        document.getElementById('fields').value = array.join(',');

    }

</script>
@endsection