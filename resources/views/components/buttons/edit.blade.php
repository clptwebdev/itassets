@props(['route','toggle','target' ,'id'=>$slot])
<a href="{{$route ?? '#'}}" id="{{$id ?? null}}" data-toggle="{!! $toggle ?? null !!}"
   data-target="{!! $target ?? null !!}" class="d-none d-sm-inline-block btn btn-sm btn-yellow shadow-sm"><i
        class="fas fa-edit fa-sm text-dark-50"></i> Edit{{$slot}}</a>
