<div class="comment-container">

    <div class="comment-box" data-comment-id = "{{$comment->id}}">

        @if(Auth::user()->is_moderator || Auth::user()->can('update', $comment))
            <div class="dropdown">
                <button class="dropbtn"></button>
                <div class="dropdown-content">
                    @can('update', $comment)
                        <a href="{{ route('update.comment', ['comment' => $comment->id]) }}" class="dropdown-button editButtonCom">Edit</a>
                    @endcan
                        <a href="" class="dropdown-button deleteButtonCom">Delete</a>
                        <form action="{{ route('delete.comment', ['comment' => $comment->id]) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                </div>
            </div>
        @endif

        <div class="comment-author">

            <div class="circle" id="commentCircle">
                <a href="{{ route('profile.show', $comment->user->id) }}" style="cursor: pointer;">
                    <img src="{{ asset('storage/profile_pictures/' . $comment->user->profile_picture_id) }}" alt="{{$comment->user->username}}">
                </a>
            </div>

            <h3 class="text-right">{{$comment->user->username}} posted the following comment</h3>

        </div>

        <div class="comment-content">

            <h4>
                <a>{{$comment->content}}</a>
            </h4>

        </div>

        <div class="comment-meta">

            @if($comment->edited)
                <p>Edited: {{\Carbon\Carbon::parse($comment->updated_at)->format('Y-m-d H:i')}} </p>
            @endif

            <p>Published: {{\Carbon\Carbon::parse($comment->created_at)->format('Y-m-d H:i')}} </p>

        </div>

    </div>

</div>