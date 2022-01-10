@props(['name','formAttributes' ,'value'=> \Carbon\Carbon::parse(now())->format('Y-m-d')])
{{--form input Dynamic || enter a valid date which is parsed--}}

<label for="name">{{str_replace(array('_','id'), ' ',ucfirst($name))}}</label>
@if(str_contains('required', $formAttributes))<span class="text-danger">*</span>@endif

<input type="date"
       class="form-control <?php if ($errors->has("{!! $name !!}")) {?>border-danger<?php }?>"
       name="{{$name}}" id="{{$name}}"
       placeholder="{{ucfirst($name)}}"
       value="{{$value ?? null}}"
    {!!$formAttributes ?? null!!}>  {{--  pass attribues seperated with spaces  --}}
