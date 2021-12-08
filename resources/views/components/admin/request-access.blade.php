<div class=" container p-3 ">
    <div class="d-flex justify-content-center ">
        <div class="">
            <img class=" img-profile" width="100px" src="{{ asset('images/lock-user.png') }}">
        </div>

    </div>
    <div class="d-flex justify-content-center">
        <div>
            <h3 class="h3 mb-2 text-gray-800 text-center">Welcome <strong
                        class="text-white bg-info rounded p-2 ">{{auth()->user()->name}}</strong> ,
                No Access has been currently granted for you! </h3>
            <hr class="my-4">
            <a href="#" class="btn btn-danger my-3 d-flex justify-content-center w-50 mx-auto">Request Access Here<i
                        class="fas fa-fingerprint p-1"></i></a>
        </div>
    </div>
</div>
