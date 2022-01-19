@props(['route' ,'id'=>$slot , 'data'=>null , 'formRequirements'=>null ])
<a href="{{$route ?? '#'}}" id="{{$id ?? null}}" data-id="{{$data}}"
   class="btn btn-sm btn-lilac shadow-sm transferBtn p-2 p-md-1 " {!! $formRequirements !!}><i
        class="fas fa-exchange-alt fa-sm text-dark-50 mr-md-l"></i><span class="d-none d-md-inline-block">Request Transfer{{$slot}}</span></a>
