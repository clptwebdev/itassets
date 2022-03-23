<!-- Profile Image Modal-->
<div class="modal fade" id="imgModal" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary-blue text-white">
                <h5 class="modal-title" id="imgModalLabel">Select Image</h5>
                <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>

            </div>
            <div class="modal-body">
                <p>Select an image below:.</p>
                <?php $photos = App\Models\Photo::all();?>
                <img src="{{ asset('images/svg/location-image.svg') }}" width="80px" alt="Default Picture"
                     onclick="selectPhoto(0, '{{ asset('images/svg/location-image.svg') }}');">
                @foreach($photos as $photo)
                    <img src="{{ asset($photo->path) }}" width="80px" alt="{{ $photo->name }}"
                         onclick="selectPhoto('{{ $photo->id }}', '{{ asset($photo->path) }}');">
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal" data-toggle="modal"
                        data-target="#uploadModal">Upload File
                </button>
            </div>
        </div>
    </div>
</div>
