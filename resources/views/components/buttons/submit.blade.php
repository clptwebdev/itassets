@props(['class'=>null])
<button type="submit" class="{{$class}} d-inline-block btn btn-sm btn-green shadow-sm"><i
        class="far fa-save fa-sm text-white-50"></i> {{ucfirst($slot)}}
</button>
