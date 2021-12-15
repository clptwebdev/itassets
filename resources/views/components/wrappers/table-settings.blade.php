<div class="dropdown no-arrow">
    <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
       id="dropdownMenuLink"
       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
    </a>
    <div
        class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
        aria-labelledby="dropdownMenuLink">
        <div class="dropdown-header text-left">Options:</div>
        {{$slot}}
    </div>
</div>
