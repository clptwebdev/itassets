@props(['route'=> '/'])
<a href="{{ $route }}"
   class="btn btn-sm btn-grey shadow-sm p-2 p-md-1"><i
        class="fas fa-chevron-left fa-sm text-white mr-lg-1"></i><span class="d-none d-lg-inline-block">Back to {{$slot}}</span></a>
