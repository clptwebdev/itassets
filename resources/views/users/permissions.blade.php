@if(count($ids) != 0)
@foreach($ids as $id)
    @if($location = \App\Models\Location::find($id))
    <div class="col-4 p-2 h-100">
        <div class="card h-100">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between h-100">
                @if ($location->photo()->exists())
                    <img src="{{ asset($location->photo->path) ?? 'null' }}" alt="{{ $location->name}}" height="40px">
                @else
                    <i class="fas fa-school fa-2x text-gray-300"></i>
                @endif
                <small style="color:{{$location->icon}}">
                    {{ $location->name}}
                </small>
                <a  href="#" onclick="javascript:removePermission({{$location->id}});" role="button">
                    <i class="fas fa-times fa-sm fa-fw text-gray-400"></i>
                </a>
            </div>
        </div>
    </div>
    @else
    <p>No Permissions Set</p>
    @endif
@endforeach  
@else
    <p>No Permissions Set</p>
@endif
