@props(['route'=> '/' , 'count'=> '0'] )
<a href="{{ $route }}" class="btn btn-sm btn-blue shadow-sm p-2 p-md-1"><i
        class="fas fa-trash-alt fa-sm text-white-50 pr-md-1"></i><span class="d-none d-md-inline-block">Recycle Bin {{$slot}} ({{ $count}})</span></a>
