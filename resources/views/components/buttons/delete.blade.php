@props(['route','toggle','target' , "formAttributes"=>null ,'id'=>$slot])<a href="{{$route ?? '#'}}"
                                                                            id="{{$id ?? null}}"
                                                                            data-bs-toggle="{!! $toggle ?? null !!}"
                                                                            {!! $formAttributes !!}data-bs-target="{!! $target ?? null !!}"
                                                                            class="btn btn-sm btn-danger shadow-sm deleteBtn p-2 p-md-1"><i
        class="fas fa-trash-alt fa-sm text-dark-50 "></i> <span class="d-none d-md-inline-block">Delete {{$slot}}</span></a>
