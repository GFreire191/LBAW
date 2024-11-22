<div class="single-question-container">

    <div class="question-box" data-question-id = "{{$question->id}}">
        @if(Auth::check())
            <div class="dropdown">
                <button class="dropbtn"></button>
                <div class="dropdown-content">
                    @if(Auth::user()->is_moderator || Auth::user()->can('update', $question))
                        @can('update', $question)
                            <a href="{{ route('update.question', ['question' => $question->id]) }}" class="dropdown-button" id="editButton">Edit</a>
                        @endcan
                            <a href="" class="dropdown-button" id="deleteButton" data-id="{{ $question->id }}">Delete</a>
                            <form id="delete-form-{{ $question->id }}" action="{{ route('delete.question', ['question' => $question->id]) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                    @endif

                    
                    @if(Auth::check() && Auth::user()->id !== $question->user->id)
                        <a href="{{ Auth::user()->isFollowing($question->id) ? route('unfollow.question', ['question' => $question->id]) : route('follow.question', ['question' => $question->id]) }}" class="dropdown-button followButton">
                            {{ Auth::user()->isFollowing($question->id) ? 'Unfollow' : 'Follow' }}
                        </a>
                    @endif
                    <a href="{{ route('create.report') }}" class="dropdown-button" id="reportButton" data-parent-id = "{{$question->id}}" data-parent-type = "question">Report</a>
                    <a href="{{ route('create.comment') }}" class="dropdown-button" id="createComment" data-parent-id = "{{$question->id}}" data-parent-type = "question">Create Comment</a>
                </div>
            </div>
        @endif
        
        
        <div id="tagButtons">
        @foreach($question->tags as $tag)
            <a href="{{ route('home', ['tag' => $tag->id]) }}" class="tagButton" data-question-id="{{ $question->id }}" data-tag-id="{{ $tag->id }}">{{$tag->name}}</a>
        @endforeach
        </div>
        <a href="{{ route('show.question', ['question' => $question->id]) }}" class="question-button" id="answerButton">Answer</a>
        <a href="{{ route('show.comment', ['type' => 'question', 'parent' => $question->id]) }}" class="question-button commentButton" data-parent-id="{{ $question->id }}" data-parent-type="question" >View Comments</a>

        <div class="question-author">

            <div class="circle">
                <a href="{{ route('profile.show',  $question->user->id) }}" style="cursor: pointer;">
                    <img src="{{ asset('storage/profile_pictures/' . $question->user->profile_picture_id) }}" alt="{{$question->user->username}}">
                </a> 
            </div>

            <h3 class="text-right">{{$question->user->username}} posted the following question</h3>

        </div>

        <div class="question-content">

            <h2>
                <a class="clickable-title" href="{{ route('show.question', $question) }}">{{$question->title}}</a>
            </h2>

            <p>{{$question->content}}</p>

        </div>
        
        <div class="question-meta">



        @if ( Auth::check()  )
        
            <div class="vote-btn">
                  
                    <a href="#" style="display: inline-block;" >

                        <img src="{{ url('images/upbtn.jpg') }}" data-question-id="{{ $question->id }}" data-vote="up"  class="voteButton_question"  alt="thumbs up icon">

                    </a>            
                        <p class= "voteCount" >{{$question->up_votes}}</p>

            </div>

            <div class="vote-btn">
                  
                    <a href="#" style="display: inline-block;" >
                        
                        <img src="{{ url('images/dwnbtn.png') }}" data-question-id="{{ $question->id }}" data-vote="down"  class="voteButton_question"  alt="thumbs down icon">

                    </a>

                        <p class= "voteCount" >{{$question->down_votes}}</p>
           
            </div>
        
        @else
                <div class="vote-btn">

                        <img src="{{ url('images/upbtn.jpg') }}" alt="thumbs up icon">

                    {{$question->up_votes}}

                </div>

                <div class="vote-btn">

                        <img src="{{ url('images/dwnbtn.png') }}" alt="thumbs down icon">

                    {{$question-> down_votes}} 

                </div>

        @endif

            @if($question->edited)
                <p>Edited: {{\Carbon\Carbon::parse($question->updated_at)->format('Y-m-d H:i')}} </p>
            @endif

            <p>Published: {{\Carbon\Carbon::parse($question->created_at)->format('Y-m-d H:i')}} </p>

        </div>

    </div>

</div>

