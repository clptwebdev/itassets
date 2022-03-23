<!-- Upload Modal -->
<div id="uploadModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imgUploadLabel">Upload Media</h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form -->
                <form id="imageUpload">
                    Name: <input type="text" placeholder="Enter File Name" name="name" class="form-control">
                    Select file : <input type='file' name='file' id='file' class='form-control'><br>
                    <button type='submit' class='btn btn-success' id='btn_upload'>Upload</button>
                </form>
            </div>

        </div>

    </div>
</div>
