@props(['route'=>'#' ,'class'=>null , 'data'=>null , 'formRequirements'=>null ,'formId'=>null])
{{$formId}}
<a href="{{ $route }}"
   class="dropdown-item {{$class}}" data-id="{{$data}}" {!! $formRequirements !!} >{{$slot}}</a>
