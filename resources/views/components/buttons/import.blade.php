@props(['route','toggle','target','id'=>$slot])<a href="{{$route ?? '#'}}" id="{{$id ?? null }}"
                                                  data-bs-toggle="{!! $toggle ?? null !!}"
                                                  data-bs-target="{!! $target ?? null !!}"
                                                  class="btn btn-sm btn-green shadow-sm p-2 p-md-1"><i
        class="fas fa-download fa-sm text-white fa-text-width mr-md-1"></i><span
        class="d-none d-md-inline-block">Import {{$slot}}</span></a>
