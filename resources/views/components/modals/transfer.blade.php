@props(['models'=>null , 'model' ,'tag'=>null])
<!-- Transfer Modal-->
<div class="modal fade bd-example-modal-lg" id="requestTransfer" tabindex="-1" role="dialog"
     aria-labelledby="requestTransferLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('request.transfer')}}" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestTransferLabel">Request to Transfer this Item to another
                                                                      Location? </h5>
                    <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="disposal_date">Asset Tag</label>
                        <input type="text" value="{{$tag}}" id="asset_tag_transfer" name="asset_tag"
                               class="form-control">
                        <small class="text-warning">Enter a new Asset Tag if required</small>
                    </div>
                    <div class="form-group">
                        @csrf
                        <input name="model_type" type="hidden" value="{{$model}}">
                        <input id="model_id" name="model_id" type="hidden" value="">
                        <input id="location_id" name="location_from" type="hidden" value="">
                        <input id="location_from" type="text" class="form-control"
                               value="{{\Carbon\Carbon::now()->format('Y-m-d')}}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="disposal_date">Date of Transfer</label>
                        <input type="date" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}" id="transfer_date"
                               name="transfer_date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="School Location">Transfer to:</label><span class="text-danger">*</span>
                        <select type="text"
                                class="form-control mb-3 @if($errors->has('location_id')){{'border-danger'}}@endif"
                                name="location_to" required>
                            <option value="0" selected>Please select a Location</option>
                            @foreach($models as $location)
                                <option
                                    value="{{$location->id}}" @if(old('location_id')== $location->id){{ 'selected'}}@endif>{{$location->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="notes">Additional Comments:</label>
                        <textarea name="notes" class="form-control" rows="5"></textarea>
                    </div>
                    <small>This will send a request to the administrator. The administrator will then decide to
                           approve or reject the request. You will be notified via email.</small>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-grey" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-lilac" type="submit">Request Transfer</button>
                </div>
            </form>
        </div>
    </div>
</div>
