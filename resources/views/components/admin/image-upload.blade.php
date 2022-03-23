@props(['model'=>false])
@if($model !== false)
    <div class="formgroup mb-2 p-2">
        <h4 class="h6 mb-3">Image</h4>
        @if($model->photo()->exists())
            <img id="profileImage" src="{{ asset($model->photo->path) ?? asset('images/svg/accessory_image.svg')}}"
                 width="100%" alt="Select Profile Picture" data-bs-toggle="modal" data-bs-target="#imgModal">
        @else
            <img id="profileImage" src="{{ asset('images/svg/accessory_image.svg') }}" width="100%"
                 alt="Select Profile Picture" data-bs-toggle="modal" data-bs-target="#imgModal">
        @endif
        <input type="hidden" id="photo_id" name="photo_id" value="0">
    </div>
@else
    <div class="w-100">
        <div class="formgroup mb-2 p-2">
            <h4 class="h6 mb-3">Image</h4>
            <img id="profileImage" src="{{ asset('images/svg/accessory_image.svg') }}" width="100%"
                 alt="Select Profile Picture" data-bs-toggle="modal" data-bs-target="#imgModal">
            <input type="hidden" id="photo_id" name="photo_id" value="0">
        </div>
    </div>
@endif
