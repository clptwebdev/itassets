@props(['route'=> '/'])
<a href="{{$route}}"
   class="d-none d-sm-inline-block btn btn-sm btn-yellow shadow-sm loading p-2 p-md-1"><i
        class="fas fa-download fa-sm text-dark-50 mr-md-1"></i><span class="d-none d-md-inline-block">Export {{$slot}}</a>
