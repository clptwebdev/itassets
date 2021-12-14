@props(['route','toggle','target' ,'id'=>$slot])
<a href="{{$route ?? '#'}}" id="{{$id ?? null}}" data-toggle="{!! $toggle ?? null !!}" data-target="{!! $target ?? null !!}" class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm"><i
        class="fas fa-plus fa-sm text-dark-50"></i> Add New {{$slot}}</a>