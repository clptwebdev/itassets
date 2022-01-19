@props(['route'=> '/'])
<a href="{{$route}}"
   class="btn btn-sm btn-yellow shadow-sm loading p-2 p-md-1"><i
        class="fas fa-download fa-sm text-dark mr-md-1"></i><span class="d-none d-md-inline-block">Export {{$slot}}</a>
