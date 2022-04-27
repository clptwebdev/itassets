@props(['model'])
<div class="d-flex flex-column flex-md-row justify-content-between align-content-center ">
    <div class="d-block d-md-inline-block">
        @if($model->hasPages())
            {!!$model->onEachSide(2)->links()!!}
        @endif
    </div>
    <div class="text-left text-md-right d-block d-md-inline-block my-auto">
        Showing Results: {{ $model->firstItem() }} to {{ $model->lastItem() }} ({{ $model->total() }} Total Results)
    </div>
</div>
