@props(['route','toggle','target' ,'id'=>$slot])
<a href="{{$route ?? '#'}}" id="{{$id ?? null}}" data-toggle="{!! $toggle ?? null !!}"
   data-target="{!! $target ?? null !!}" class="btn btn-sm btn-yellow shadow-sm p-2 p-md-1 mr-md-1"><i
        class="fas fa-edit fa-sm text-dark-50 mr-md-1"></i><span class="d-none d-lg-inline-block">Edit{{$slot}}</span></a>
