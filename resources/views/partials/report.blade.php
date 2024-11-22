<div class="report-box">
    <div class="report-summary">
        <h2>Report ID: {{ $report->id }}</h2>
        <p>Reported by User ID: {{ $report->user_id }} - <a href="{{ route('profile.show', $report->user->id) }}">{{ $report->user->username }}</a></p>
        <p>Reported Entity ID: {{ $report->parent_id }}</p>
        <div class="expand-arrow"></div>
    </div>
    <div class="report-details">
        <p>Motive: {{ $report->motive }}</p>
        <p>Reported at: {{ \Carbon\Carbon::parse($report->created_at)->format('Y-m-d H:i') }}</p>
        <div class="question-box" data-question-id = "{{$report->parent->id}}" id="question-in-report">
    
            <div class="question-author">

                <div class="circle">
        
                        <img src="{{ asset('storage/profile_pictures/' . $report->parent->user->profile_picture_id) }}" alt="{{$report->parent->user->username}}">      
                </div>

                @if ($report->parent instanceof \App\Models\Question)
                    <h3 class="text-right">{{$report->parent->user->username}} posted the following question</h3>
                @else
                    <h3 class="text-right">{{$report->parent->user->username}} posted the following answer</h3>
                @endif

            </div>

            <div class="question-content">

                <h2>
                    <a class="clickable-title" href="{{ route('show.question', $report->parent) }}">{{$report->parent->title}}</a>
                </h2>

                <p>{{$report->parent->content}}</p>

            </div>

            <div class="question-meta">

                

                <p>UpVotes: {{$report->parent-> up_votes}} </p>

                <p>DownVotes: {{$report->parent-> down_votes}} </p>


                @if($report->parent->edited)
                    <p>Edited: {{\Carbon\Carbon::parse($report->parent->updated_at)->format('Y-m-d H:i')}} </p>
                @endif

                <p>Published: {{\Carbon\Carbon::parse($report->parent->created_at)->format('Y-m-d H:i')}} </p>

            </div>

        </div>

        <div class="report-actions">
        
            <form id="delete-report-form" action="{{ route('delete.report', $report) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="question-button">Keep Entity</button>
            </form>
            
            @if ($report->parent instanceof \App\Models\Question)
                <form id="delete-question-form" action="{{ route('delete.question', $report->parent->id) }}" method="POST">
            @else
                <form id="delete-answer-form" action="{{ route('delete.answer', $report->parent->id) }}" method="POST">
            @endif
                @csrf
                @method('DELETE')
                <button type="submit" class="question-button">Delete Entity</button>
            </form>
        
        </div>
    </div>  
</div>