@props(['route','toggle','target' ,'id'=>$slot])<a href="{{$route ?? '#'}}" id="{{$id ?? null}}"
                                                   data-bs-toggle="{!! $toggle ?? null !!}"
                                                   data-bs-target="{!! $target ?? null !!}"
                                                   class="btn btn-sm btn-green shadow-sm p-2 p-md-1"><i
        class="fas fa-plus fa-sm text-dark-50 mr-md-1"></i><span
        class="d-none d-md-inline-block">Add New {{$slot}}</span></a>
