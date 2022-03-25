<!-- Disposal Modal-->
<div class="modal fade bd-example-modal-lg" id="requestDisposal" tabindex="-1" role="dialog"
     aria-labelledby="requestDisposalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('request.disposal')}}" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestDisposalLabel">Request to Dispose/Archive of
                                                                      the {{ucfirst($model)}}? </h5>
                    <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        @csrf
                        <input name="model_type" type="hidden" value="{{$model}}">
                        <input id="dispose_id" name="model_id" type="hidden" value="">
                        <input type="text" value="" id="model_name" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="disposal_date">Date of Disposal</label>
                        <input type="date" value="" id="disposed_date" name="disposed_date" class="form-control"
                               value="{{\Carbon\Carbon::now()->format('Y-m-d')}}">
                    </div>
                    <div class="form-group">
                        <label for="notes">Reasons for:</label>
                        <textarea name="notes" class="form-control" rows="5"></textarea>
                    </div>
                    <small>This will send a request to the administrator. The administrator will then decide to
                           approve or reject the request. You will be notified via email.</small>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-grey" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-coral" type="submit">Request Disposal/Archive</button>
                </div>
            </form>
        </div>
    </div>
</div>
