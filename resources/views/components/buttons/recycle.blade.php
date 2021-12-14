@props(['route'=> '/' , 'count'=> '?'] )
<a href="{{ $route }}" class="d-none d-sm-inline-block btn btn-sm btn-blue shadow-sm"><i
        class="fas fa-trash-alt fa-sm text-white-50"></i>Recycle Bin {{$slot}} ({{ $count}})</a>
