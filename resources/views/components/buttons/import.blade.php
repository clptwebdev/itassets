@props(['route','toggle','target','id'=>$slot])
<a href="{{$route ?? '#'}}" id="{{$id ?? null }}" data-toggle="{!! $toggle ?? null !!}" data-target="{!! $target ?? null !!}" 
class="btn btn-sm btn-green shadow-sm p-2 p-md-1"><i
        class="fas fa-download fa-sm text-white-50 fa-text-width mr-md-1"></i><span class="d-none d-md-inline-block">Import {{$slot}}</span></a>
