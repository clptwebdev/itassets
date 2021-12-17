
<div class="d-flex flex-column justify-content-center h-100 container my-3">
    <div class="d-flex justify-content-center align-items-center mb-4 pb-4">
        <i class="fas fa-user-lock fa-10x"></i>
    </div>
    <div class="text-center border border-gray m-auto p-4 bg-white shadow" style="max-width: 600px;">
        <h3 class="h3 mb-2 text-gray-800 ">Welcome {{auth()->user()->name}}</h3>
        <hr class="my-4">
        <h4 class="text-coral">No Access has been currently granted for you!</h4>
        <p>Thank you for using the Apollo Asset Manager at Central Learning Partnership Trust (CLPT)</p>
        <p>You currently don't have a role set and permission granted for any of the schools in the trust. To reuqest access you can
            click the button below. If you are having trouble or have not received a response to your request, please email <a href="mailto:apollo@clpt.co.uk">apollo@clpt.co.uk</a>.
        </p>
    </div>
    <div class="text-center my-4">
        <a href="{{ route('requests.access')}}" class="btn btn-lilac">Request Access Here<i
            class="fas fa-fingerprint p-1"></i></a>
    </div>
</div>
