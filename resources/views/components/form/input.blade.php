@props(['name'=> 'title', 'title' ,'formAttributes'=>" " ,'value' => null , 'type'=>'text' , 'label'=>true , 'switch' =>false])

{{--form input Dynamic--}}
@if($label == true)
    <label
        for="{{$name}}">{{str_replace(array('_','id'), ' ',ucfirst($title ?? $name))}}</label>
    @if(isset($formAttributes))

        @if(str_contains($formAttributes,'required' ))<span class="text-danger">*</span>@endif
    @endif
@endif

<input type="{{$type}}" class="form-control
    {{-- for assets switch case feedback--}}
    @if($switch == false)
        @if($errors->has('{!! $name !!}'))  {!! 'border-danger' !!}@endif
    @elseif($switch == true)
        @if ($errors->has(str_replace(' ', '_', strtolower($name))))  {!! 'border-danger' !!} @endif
    @endif"

    @if($switch == false)
        name="{{$name}}"
        value="{{old("{!! $name !!}") ?? $value}}"
    @elseif($switch == true)
        name="{{str_replace(' ', '_', strtolower($field->name))}}"
        value="{{ old(str_replace(' ', '_', strtolower($field->name)))}}"
    @endif
        {{-- END--}}
    id="{{$name}}" placeholder="{{str_replace(array('_','id'), ' ',ucfirst($title ?? $name))}}"
    {!!$formAttributes ?? null!!}
>  {{--  pass attribues seperated with spaces  --}}


name="operating_system"