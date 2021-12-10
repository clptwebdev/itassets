<div class="modal fade" id="newModel" tabindex="-1" role="dialog" aria-labelledby="newModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="newModelLabel">Create a New Asset Modal</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
            
            @csrf

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text"
                    class="form-control <?php if ($errors->has('name')) {?>border-danger<?php }?>"
                    name="model_name" id="model_name" placeholder="">
            </div>
            <div class="form-group">
                <label for="manufacturer">Manufacturer:</label>
                <select class="form-control mb-3 <?php if ($errors->has('manufacturer_id')){?>border-danger<?php }?>"
                    name="manufacturer_id" id="manufacturer_id" required>
                    <option value="0">Please select a Manufacturer</option>
                    @foreach($mans->sortBy('name') as $man)
                    <option value="{{$man->id}}">{{ $man->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="model_no">Model No:</label>
                <input type="text"
                    class="form-control mb-3 <?php if ($errors->has('model_no')){?>border-danger<?php }?>"
                    name="model_no" id="model_no" placeholder="#" required>
            </div>
            <div class="form-group">
                <label for="depreciation_id">Depreciation</label>
                <select
                    class="form-control <?php if ($errors->has('depreciation_id')){?>border-danger<?php }?>"
                    name="depreciation_id" id="depreciation_id" required>
                    <option value="0">No Depreciation Set</option>
                    @foreach($depreciation as $dep)
                        <option value="{{ $dep->id}}">{{ $dep->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="eol">EOL (End of Life) Months</label>
                <input type="text" class="form-control" name="eol" id="eol"
                    placeholder="36">
            </div>
            <div class="form-group">
                <label for="fieldset_id">Additional Fieldsets</label>
                <select class="form-control" name="fieldset_id" id="fieldset_id">
                    <option value="0">No Additional Fieldsets Required</option>
                    @foreach($fieldsets as $fieldset)
                    <option value="{{ $fieldset->id }}">{{ $fieldset->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea type="text" class="form-control" rows="5" name="notes" id="notes"></textarea>
            </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submitButton">Save changes</button>
        </div>
    </div>
    </div>
</div>