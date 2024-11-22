<div class="single-answer-container">

    <div class="answer-box" data-answer-id = "{{$answer->id}}">
    
        @if(Auth::check())
            <div class="dropdown">
                <button class="dropbtn"></button>
                <div class="dropdown-content">
                    @if(Auth::user()->is_moderator || Auth::user()->can('update', $answer))
                            @can('update', $answer)
                                <a href="{{ route('update.answer', ['answer' => $answer->id]) }}" class="dropdown-button" id="editButtonAn">Edit</a>
                            @endcan
                            <a href="" class="dropdown-button" id="deleteButtonAn" data-id="{{ $answer->id }}">Delete</a>
                            <form id="delete-form-{{ $answer->id }}" action="{{ route('delete.answer', ['answer' => $answer->id]) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                    @endif

                    <a href="{{ route('create.report') }}" class="dropdown-button" id="reportButton" data-parent-id = "{{$answer->id}}" data-parent-type = "answer">Report</a>
                    <a href="{{ route('create.comment') }}" class="dropdown-button" id="createComment" data-parent-id = "{{$answer->id}}" data-parent-type = "answer">Create Comment</a>
                </div>
            </div>
        @endif

        <a href="{{ route('show.comment', ['type' => 'answer', 'parent' => $answer->id]) }}" class="question-button commentButton" id="commentButtonAns" data-parent-id="{{ $answer->id }}" data-parent-type="answer" >View Comments</a>

        <div class="answer-author">
            
            <div class="circle" id="answerCircle">

                <a href="{{ route('profile.show', $answer->user->id) }}" style="cursor: pointer;">
                    <img src="{{ asset('storage/profile_pictures/' . $answer->user->profile_picture_id) }}" alt="{{$answer->user->username}}">
                </a>
            </div>

            <h3 class="text-right">{{$answer->user->username}} posted the following answer</h3>

        </div>

        <div class="answer-content" data-url="{{ route('show.question', $answer->question->id) }}">

            <h3>
                <a class="clickable-title" href="{{ route('show.question', $answer->question->id) }}">{{$answer->content}}</a>
            </h3>

            

        </div>

        <div class="answer-meta">

            @if ( Auth::check()  )
            
                <div class="vote-btn">
                    
                        <a href="#" style="display: inline-block;" >

                            <img src="{{ url('images/upbtn.jpg') }}" data-answer-id="{{ $answer->id }}" data-vote="up" class="voteButton_answer" alt="thumbs up icon">

                        </a>                
                        <p class= "voteCount" >{{$answer->up_votes}}</p>

                </div>

                <div class="vote-btn">
                    
                        <a href="#" style="display: inline-block;" >
                            <!-- change it visually when voted already -->
                            <img src="{{ url('images/dwnbtn.png') }}" data-answer-id="{{ $answer->id }}" data-vote="down" class="voteButton_answer" alt="thumbs down icon">

                        </a>
                        <p class= "voteCount" >{{$answer->down_votes}}</p>

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

                <p>Published: {{\Carbon\Carbon::parse($answer->created_at)->format('Y-m-d H:i')}} </p>

            </div>
            

            @if (Auth::check() && $answer->question->user_id == Auth::user()->id )
                @if($answer->correct == 1)
                    <div class="answer-correct" >
                        
                        <button class="correct-button remove-correct" data-correct="1"></button>

                    </div>
                @else
                    <div class="answer-correct">

                        <button class="correct-button mark-correct" data-correct="0"></button>

                    </div>
                
                @endif

        @else
            @if($answer->correct == 1)
                <div class="answer-correct">

                    <button class="correct-button correct" data-correct="1"></button>

                </div>
            @endif
        @endif

        
        
            

        

        

    </div>

</div>
