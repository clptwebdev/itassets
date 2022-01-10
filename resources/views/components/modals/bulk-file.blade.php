@props(['route'=>'/','inputName' => 'csv' , 'title'=>'' ])
{{-- This is the Modal for Bulk options {SC} --}}
<div class="modal fade bd-example-modal-lg" id="bulk{{ucfirst($title)}}Modal" tabindex="-1"
     role="dialog"
     aria-labelledby="bulk{{ucfirst($title)}}ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulk{{ucfirst($title)}}ModalLabel">Bulk {{ucfirst($title)}}
                    Assets -
                    Upload</h5>
                Upload</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{$route}}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p>Select "import {{ucfirst($title)}}(s)" to bulk edit Assets on the system</p>
                    <input class="form-control shadow-sm"
                           type="file" placeholder="Upload here" name="{{$inputName}}" accept=".csv"
                           id="{{lcfirst($title)}}Assets"
                           required>
                </div>
                <div class="modal-footer">
                    @if(session('import-error'))
                        <div class="alert text-warning ml-0"> {{ session('import-error')}} </div>
                    @endif
                    <button class="btn btn-grey" type="button" data-dismiss="modal">Cancel</button>

                    <button type="submit" class="btn btn-danger" type="button">
                        Import {{ucfirst($title)}}(s)
                    </button>

                    @csrf
                </div>
            </form>
        </div>
    </div>
</div>
