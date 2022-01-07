@props(['route' ,'id'=>$slot , 'data'=>null , 'formRequirements'=>null ])
<a href="{{$route ?? '#'}}" id="{{$id ?? null}}" data-id="{{$data}}"
   class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm disposeBtn" {!! $formRequirements !!}><i
        class="fas fa-trash fa-sm text-dark-50"></i> Request Disposal{{$slot}}</a>
