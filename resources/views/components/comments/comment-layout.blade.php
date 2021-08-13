@props(["comment"])
<div class="col-4-lg m-auto">
    <div class="card text-white  mb-3 mt-3"
         style="max-width: 18rem; background-color:#474775; min-height: 15.5rem; border-radius:10%; ">
        <div class=" border-bottom-light d-flex" style="border-radius: 5%">
            <h3 class="p-2 d-flex justify-content-start">{{ $comment->title }}</h3>
            <div class="  ml-auto p-2">
                <div class="dropdown no-arrow">
                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                         aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">comment Options:</div>
                        <a href="{{ route('comment.edit', $comment->id) }}" class="dropdown-item">Edit</a>
                        <form id="form{{$comment->id}}" action="{{ route('comment.destroy', $comment->id) }}"
                              method="POST" class="d-block p-0 m-0">
                            @csrf
                            @method('DELETE')
                            <a id="comment_button" class="deleteBtn dropdown-item" href="#"
                               data-id="{{$comment->id}}">Delete</a>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <p class=" pl-2 pr-2 text-lg-right text-gray-300"
           style="background-color:#474775; ">Updated Last by:{{ $comment->user->name?? 'N/A' }}</p>
        <div class="card-body ">
            <p class="card-text"><strong>Details:</strong> {{ $comment->comment }}</p>
            <p class="text-gray-300 ">Last Updated: {{$comment->updated_at}}</p>
        </div>
    </div>
</div>
