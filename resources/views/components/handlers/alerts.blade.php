@if(session('danger_message'))
    <div class="alert alert-danger"> {!! session('danger_message')!!} </div>
@endif

@if(session('success_message'))
    <div class="alert alert-success"> {!!session('success_message')!!} </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
