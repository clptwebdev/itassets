@props(['action' =>null , 'enctype' =>null , 'method'=> false , 'class'=>null])
<form action="{{ $action }}" method="POST" enctype="{{$enctype}}" class="{!! $class !!}" >
    @csrf
    @if($method != false){{method_field($method)}}@endif
{!! $slot !!}
</form>

