@props(['route','toggle','target' , "formAttributes"=>null ,'id'=>$slot])
<a href="{{$route ?? '#'}}" id="{{$id ?? null}}" data-toggle="{!! $toggle ?? null !!}" {!! $formAttributes !!}
data-target="{!! $target ?? null !!}" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm deleteBtn"><i
        class="fas fa-trash-alt fa-sm text-dark-50"></i> Delete{{$slot}}</a>
