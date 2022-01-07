@props(['route'=> '/'  ] )
<a href="{{ $route }}" class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
        class="fas fa-file-pdf fa-sm text-dark-50"></i> Generate Report {{$slot}}</a>
