@props(['name'=> 'title', 'title' ,'formAttributes'=>" " ,'value' => '' , 'type'=>'text' , 'label'=>true ])

{{--form input Dynamic--}}
@if($label == true)
    <label
        for="{{$name}}" >{{str_replace(array('_','id'), ' ',ucfirst($title ?? $name))}}</label >
    @if(isset($formAttributes))

        @if(str_contains($formAttributes,'required' ))<span class="text-danger" >*</span >@endif
    @endif
@endif
<input type="{{$type}}"
       class="form-control @if ($errors->has(str_replace(' ', '_', strtolower($name))))  {!! 'border-danger' !!} @endif"
       name="{{str_replace(' ', '_', strtolower($name))}}"
       value="{{$value ?? ''}}"
       id="{{ old(str_replace(' ', '_', strtolower($name)))}}"
       placeholder="{{str_replace(array('_','id'), ' ',ucfirst($title ?? $name))}}"
    {!!$formAttributes ?? null!!}
>  {{--  pass attribues seperated with spaces  --}}
