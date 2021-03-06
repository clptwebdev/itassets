@props(['name','formAttributes', 'value' => ''])

{{--form input Dynamic || value should be current data stored--}}

<label for="name">{{str_replace(array('_','id'), ' ',ucfirst($name))}}</label>
@if(isset($formAttributes))

    @if(str_contains($formAttributes,'required' ))<span class="text-danger">*</span>@endif
@endif
<textarea name="{{str_replace(' ', '_', strtolower($name))}}" value='{{$value}}'
          id="{{str_replace(' ', '_', strtolower($name))}}"
          class="form-control @if ($errors->has(str_replace(' ', '_', strtolower($name))))  {!! 'border-danger' !!} @endif"
    {!! str_replace('required' , '', $formAttributes  ?? null)!!}
>{{old(str_replace(' ', '_', strtolower($name))) ?? $value}}</textarea>

{{--  pass attribues seperated with spaces  --}}
