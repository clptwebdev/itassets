@props(['route'=> '/'])
<a href="{{$route}}"
   class="d-none d-sm-inline-block btn btn-sm btn-yellow shadow-sm loading"><i
        class="fas fa-download fa-sm text-dark-50"></i> Export {{$slot}}</a>
