@props(['route'=> '/'])
<a href="{{ $route }}"
   class="d-none d-sm-inline-block btn btn-sm btn-grey shadow-sm"><i
        class="fas fa-chevron-left fa-sm text-white-50"></i> Back to {{$slot}}</a>
