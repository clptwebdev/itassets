@props(['class'=> 'btn-green', 'icon' => 'far fa-save'])
<button type="submit" class="{{$class}} d-inline-block btn btn-sm  shadow-sm p-2 p-md-1"><i
        class="{{$icon}} fa-sm text-white pr-md-1"></i><span
        class="d-none d-md-inline-block">{!!ucfirst($slot)!!}</span>
</button>
