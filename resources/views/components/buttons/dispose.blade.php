@props(['route' ,'id'=>$slot , 'data'=>null , 'formRequirements'=>null ])
<a href="{{$route ?? '#'}}" id="{{$id ?? null}}" data-id="{{$data}}"
   class="btn btn-sm btn-danger shadow-sm disposeBtn p-2 p-md-1" {!! $formRequirements !!}><i
        class="fas fa-trash fa-sm text-dark-50 mr-lg-1"></i><span class="d-none d-lg-inline-block">Request Disposal{{$slot}}</span></a>
