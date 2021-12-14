@props(['route'=> '/'])
<a href="{{$route}}"
   class="d-none d-sm-inline-block btn btn-sm  bg-yellow shadow-sm"><i
        class="fas fa-question fa-sm text-dark-50"></i> Need Help {{$slot}}?</a>
