@props(['name','formAttributes' ,'value'=> \Carbon\Carbon::parse(now())->format('Y-m-d')]){{--form input Dynamic || enter a valid date which is parsed--}}

<label for="name">{{str_replace(array('_','id'), ' ',ucfirst($name))}}</label>
@if(isset($formAttributes))

    @if(str_contains($formAttributes,'required' ))<span class="text-danger">*</span>@endif
@endif

<input type="date"
       class="form-control @if ($errors->has(str_replace(' ', '_', strtolower($name))))  {!! 'border-danger' !!} @endif"
       name="{{str_replace(' ', '_', strtolower($name))}}" id="{{str_replace(' ', '_', strtolower($name))}}"
       placeholder="{{ucfirst($name)}}" id="{{str_replace(' ', '_', strtolower($name))}}" value="{{$value ?? null}}"
    {!! str_replace('required' , '', $formAttributes) ?? null!!} >  {{--  pass attribues seperated with spaces  --}}
