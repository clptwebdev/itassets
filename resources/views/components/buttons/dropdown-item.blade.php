@props(['route'=>'#' ,'class'=>null , 'data'=>null , 'formRequirements'=>null ,'formId'=>null , 'id'=>'id'])
{{$formId}}
<a href="{{ $route }}"
   id="{{$id}}" class="dropdown-item {{$class}}" data-id="{{$data}}" {!! $formRequirements !!} >{{$slot}}</a>
