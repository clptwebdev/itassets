@props(['name','formAttributes' ,'value' => null])

{{--form input Dynamic--}}

<label for="name">{{str_replace(array('_','id'), ' ',ucfirst($name))}}</label>

<input type="text"
       class="form-control <?php if ($errors->has("{!! $name !!}")) {?>border-danger<?php }?>"
       name="{{$name}}" id="{{$name}}"
       placeholder="{{ucfirst($name)}}"
       value="{{old("{!! $name !!}") ?? $value}}"
      {!!$formAttributes ?? null!!}>  {{--  pass attribues seperated with spaces  --}}
