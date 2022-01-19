@props(['route'=> '/'  ] )
<a href="{{ $route }}" class="btn btn-sm btn-grey shadow-sm p-2 p-md-1"><i
        class="fas fa-file-pdf fa-sm text-dark-50 mr-md-1"></i><span class="d-none d-md-inline-block">Generate Report {{$slot}}</span></a>
