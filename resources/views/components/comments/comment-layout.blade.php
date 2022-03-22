@props(["asset"])
<div class="card shadow h-100">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold">Comments</h6>
        <button id="commentModal" class="d-none d-sm-inline-block btn btn-sm btn-green shadow-sm">
            Add New Comment
        </button>
    </div>
    <div class="card-body">
        <div class="row no-gutters">
            <div class="col mr-2">
                <div class="mb-1">
                    <table id="comments" class="table table-striped ">
                        <thead>
                        <tr class="d-none">
                            <th><small>Comments</small></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($asset->comment as $comment)
                            <tr>
                                <td class="text-left" data-sort="{{ strtotime($comment->created_at)}}">
                                    <strong>{{$comment->title}}</strong><br>
                                    {{$comment->comment}}<br>
                                    <small><span
                                            class="text-info">{{ $comment->user->name }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $comment->created_at, 'Europe/London');}}</span></small>
                                </td>
                                <td class="col-1 text-right">
                                    <div class="dropdown no-arrow">
                                        <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                           id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                           aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div
                                            class="dropdown-menu text-right dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">comment Options:</div>
                                            @can('update', $comment)
                                                <a href="#" class="dropdown-item editComment"
                                                   data-route="{{ route('comment.update', $comment->id)}}"
                                                   data-id="{{ $comment->id}}" data-title="{{ $comment->title}}"
                                                   data-comment="{{ $comment->comment}}">Edit</a>
                                            @endcan
                                            @can('delete', $comment)
                                                <form id="comment{{$comment->id}}"
                                                      action="{{ route('comment.destroy', $comment->id) }}"
                                                      method="POST" class="d-block p-0 m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" id="commentModal" class="dropdown-item deleteComment "
                                                       data-route="{{route('comment.destroy' , $comment->id)}}"
                                                       data-id="{{$comment->id}}">Delete</a>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



