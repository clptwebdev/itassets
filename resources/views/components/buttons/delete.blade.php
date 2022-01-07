@props(['route','toggle','target' ,'id'=>$slot])
<a href="{{$route ?? '#'}}" id="{{$id ?? null}}" data-toggle="{!! $toggle ?? null !!}"
   data-target="{!! $target ?? null !!}" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
        class="fas fa-trash-alt fa-sm text-dark-50"></i>Delete{{$slot}}</a>
