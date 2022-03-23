<!-- timeout-log Modal-->
<div class="modal fade bd-example-modal-lg" id="timeoutModal" tabindex="-1" role="dialog"
     aria-labelledby="timeoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-blue" id="timeoutModalLabel">Your Session is about to timeout.</h4>
                <button class="btn btn-light" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" onclick="resetCounter()">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p class='text-center text-blue'>Please select one of the option below to End the session or
                                                 Re-connect.</p>
            </div>
            <div class="modal-footer">
                <a class="btn-yellow btn" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-white"></i> {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                <a class="btn-green btn" id='reconncect' href="#" onclick="resetCounter()">
                    <i class="fas fa-check-circle fa-sm fa-fw mr-2 text-white"></i> Re-Connect
                </a>
            </div>
        </div>
    </div>
</div>

