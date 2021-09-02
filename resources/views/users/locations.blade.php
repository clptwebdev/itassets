@foreach($user->locations as $location)
<div class="col-12 mb-2">
    <div class="card shadow" style="background-color: {{$location->icon}}; color: #FFF">
        <div class="card-body">
            <div class="row d-flex">
                <div>
                    @if(isset($location->photo->path))
                        <img src="{{ asset($location->photo->path)}}" height="50px" alt="{{$location->name}}" title="{{ $location->name ?? 'Unnassigned'}}"/>
                    @else
                        {!! '<span class="display-5 font-weight-bold btn btn-sm rounded-circle text-white" style="background-color:'.strtoupper($location->icon ?? '#666').'">'
                            .strtoupper(substr($location->name ?? 'u', 0, 1)).'</span>' !!}
                    @endif
                </div>
                <div class="pl-3">
                    {{ $location->name}}
                    <div class="text-white-50 small">{{$location->icon}}</div>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endforeach