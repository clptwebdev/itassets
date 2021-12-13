@props(['model'])
<div class="d-flex justify-content-between align-content-center">
    <div>
        @if($model->hasPages())
            {{ $model->links()}}
        @endif
    </div>
    <div class="text-right">
        Showing Assets {{ $model->firstItem() }} to {{ $model->lastItem() }} ({{ $model->total() }} Total Results)
    </div>
</div>
