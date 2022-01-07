@props(['route' ,'id'=>$slot , 'data'=>null , 'formRequirements'=>null ])
<a href="{{$route ?? '#'}}" id="{{$id ?? null}}" data-id="{{$data}}"
   class="d-none d-sm-inline-block btn btn-sm btn-lilac shadow-sm transferBtn " {!! $formRequirements !!}><i
        class="fas fa-exchange-alt fa-sm text-dark-50"></i> Request Transfer{{$slot}}</a>
