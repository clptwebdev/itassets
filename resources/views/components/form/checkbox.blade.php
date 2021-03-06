@props(['name','formAttributes' ,'models' ,'checked' => null]){{--checked value should pass foreach of all Relationship categories pushed into an array--}}
<h4 class="h6 mb-4 text-center">{{str_replace(array('_','id'), ' ',ucfirst($name))}}</h4>
@if(isset($formAttributes))

    @if(str_contains($formAttributes,'required' ))<span class="text-danger">*</span>@endif
@endif
{{--enter your models plural name--}}
@foreach($models as $model)
    <div class="form-check form-check-inline mr-2 p-2 ">
        <input class="form-check-input" type="checkbox" value="{{ $model->id }}"
               name="{!! str_replace(array('id','_') , '' , strtolower($name)) !!}[]"
               id="{!! str_replace(array('id','_') , '' , strtolower($name)) !!}{{$model->id}}"
        @if(!$checked == null)
            @if(in_array($model->id, $checked)){{ 'checked'}}@endif
            @endif
            {!! str_replace('required' , '', $formAttributes ?? null) !!}
        >

        <label class="form-check-label "
               for="{!! str_replace(array('id','_') , '' , strtolower($name)) !!}{{$model->id}}">{{ $model->name }}</label>
    </div>
@endforeach
