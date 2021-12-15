@props(['action' =>null , 'enctype' =>null , 'method'=> false , 'class'=>null ,'component'=>null , 'id'=>null])
<form action="{{ $action }}" method="POST" enctype="{{$enctype}}" class="{!! $class !!}" id="{{$id}}">
    @csrf
    @if($method != false){{method_field($method)}}@endif
{!! $slot !!}
</form>

