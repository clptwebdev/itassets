@foreach($fieldset->fields as $field)
<div class="form-group">
    <label for="{{strtolower($field->name)}}">{{$field->name}}</label>
 @switch($field->type)
    @case('Text')
        <input type="text" class="form-control" name="{{strtolower($field->name)}}" placeholder="{{ $field->name }}"> 
        @break
    @case('Textarea')
        <textarea name="{{strtolower($field->name)}}" id="" cols="30" rows="10" class="form-contol"></textarea>
        @break
    @case('Select')
        <?php $array = explode("\r\n", $field->value);?>
        <select name="{{strtolower($field->name)}}" class="form-control">
            @foreach($array as $id=>$key)
            <option value="{{ $key }}">{{ $key }}</option>
            @endforeach
        </select>
        @break
    @case('Checkbox')
        <?php $array = explode("\r\n", $field->value);?>
        @foreach($array as $id=>$key)
            <br><input type="checkbox" name="{{strtolower($field->name)}}[]" value="{{ $key }}"> <label>&nbsp;{{ $key }}</label>
        @endforeach
        @break
    @default
         <input type="text" class="form-control" name="{{strtolower($field->name)}}" placeholder="{{ $field->name }}">
 @endswitch
    </div>
@endforeach