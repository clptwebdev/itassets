<!-- timeout-log Modal-->
<div class="modal fade bd-example-modal-lg " id="timedoutModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="timedoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-blue" id="timedoutModalLabel">Your Session has Timed out.</h4>
                {{--                <button class="btn btn-light" type="button" data-bs-dismiss="modal" aria-label="Close">--}}
                {{--                    <span aria-hidden="true">×</span>--}}
                {{--                </button>--}}
            </div>
            <div class="modal-body row">
                <div class='d-flex justify-content-center align-items-center col-12'>
                    <img height='300px' src='{{asset('images/png/timeout.png')}}'>
                </div>
                <p class='mx-auto text-center text-blue'>Please Log Back in before completing anymore Actions.</p>
            </div>
            <div class="modal-footer">
                <a class='btn btn-green' href="{{route('dashboard')}}"> <i
                        class="fas fa-home fa-sm fa-fw mr-2 text-white"></i>Re-Connect</a>
            </div>
        </div>
    </div>
</div>




