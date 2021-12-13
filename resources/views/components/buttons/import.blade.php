@props(['route','toggle','target','id'=>$slot])
<a href="{{$route ?? '#'}}" id="{{$id ?? null }}" data-toggle="{!! $toggle ?? null !!}" data-target="{!! $target ?? null !!}" class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm"><i
        class="fas fa-download fa-sm text-white-50 fa-text-width"></i> Import {{$slot}}</a>
