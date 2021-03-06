@props(['title'=> 'Page View'])
<div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ucfirst($title ?? null)}}</h1>
    <div id="subMenu">
        {!!$slot!!}
    </div>
</div>

