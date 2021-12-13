@props(['action' =>null , 'enctype' =>null , 'method'=> false , 'class'=>null])
<form action="{{ $action }}" method="POST" enctype="{{$enctype}}" class="{!! $class !!}" >
    @csrf
    @if($method != false)@method("{$method}")@endif
{!! $slot !!}
</form>

