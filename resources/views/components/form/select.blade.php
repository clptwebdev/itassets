@props(['name','formAttributes' => '' ,'models' ,'selected' => 0 ,'id' => $name]){{--defaults the selcted collumns if not set to edit and with id || selected value should be the id of the related model --}}

<label for="{{$name}}">{{str_replace(array('_','id'), ' ',ucfirst($name))}}</label>
@if(isset($formAttributes))

    @if(str_contains($formAttributes,'required' ))<span class="text-danger">*</span>@endif
@endif
<select type="text"
        class="form-control @if ($errors->has(str_replace(' ', '_', strtolower($name))))  {!! 'border border-danger' !!} @endif"
        id="{{$id}}"
        name="{{str_replace(' ', '_', strtolower($name))}}" {!! str_replace('required' , '', $formAttributes ?? null) !!}>
    <option value="0" @if(old(str_replace(' ', '_', strtolower($name))) == 0){{'selected'}}@endif>
        No {{str_replace(array('_','id'), ' ',ucfirst($name))}}</option>
    @foreach($models as $model)
        <option
            value="{{ $model->id }}" @if(old(str_replace(' ', '_', strtolower($name))) == $model->id ){{'selected'}}@endif @if(($selected == $model->id)){{'selected'}}@endif>{{ $model->name}}
        </option>
    @endforeach
</select>
