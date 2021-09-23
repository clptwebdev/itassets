@foreach($fieldset->fields as $field)
<div class="form-group">
    <label for="{{str_replace(' ', '_', strtolower($field->name))}}">{{$field->name}}</label>
 @switch($field->type)
    @case('Text')
        <input type="text" class="form-control" name="{{str_replace(' ', '_', strtolower($field->name))}}" placeholder="{{ $field->name }}" @if($field->required == 1){{'required'}}> 
        @break
    @case('Textarea')
        <textarea name="{{str_replace(' ', '_', strtolower($field->name))}}" id="" cols="30" rows="10" class="form-contol" @if($field->required == 1){{'required'}}></textarea>
        @break
    @case('Select')
        <?php $array = explode("\r\n", $field->value);?>
        <select name="{{str_replace(' ', '_', strtolower($field->name))}}" class="form-control" @if($field->required == 1){{'required'}}>
            @foreach($array as $id=>$key)
            <option value="{{ $key }}">{{ $key }}</option>
            @endforeach
        </select>
        @break
    @case('Checkbox')
        <?php $array = explode("\r\n", $field->value);?>
        @foreach($array as $id=>$key)
            <br><input type="checkbox" name="{{str_replace(' ', '_', strtolower($field->name))}}[]" value="{{ $key }}"> <label>&nbsp;{{ $key }}</label>
        @endforeach
        @break
    @default
         <input type="text" class="form-control" name="{{str_replace(' ', '_', strtolower($field->name))}}" placeholder="{{ $field->name }}" required>
 @endswitch
    </div>
@endforeach