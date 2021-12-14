@props(['name'=> 'title', 'title' ,'formAttributes' ,'value' => null , 'type'=>'text' , 'label'=>true])

{{--form input Dynamic--}}
@if($label == true)
<label for="{{$name}}">{{str_replace(array('_','id'), ' ',ucfirst($title ?? $name))}}</label>
@endif
<input type="{{$type}}"
       class="form-control  <?php if ($errors->has("{!! $name !!}")) {?>border-danger<?php }?>"
       name="{{$name}}" id="{{$name}}"
       placeholder="{{str_replace(array('_','id'), ' ',ucfirst($title ?? $name))}}"
       value="{{old("{!! $name !!}") ?? $value}}"
      {!!$formAttributes ?? null!!}>  {{--  pass attribues seperated with spaces  --}}
