<div class="notification-container">

    <div class="notification-box" data-notification-id = "{{$notification->id}}">
        
        @if(Auth::user()->is_moderator || Auth::user())
                <a href="" class="question-button" id="clear-button">Clear</a>
                <form action="{{ route('delete.notification', ['notification' => $notification->id]) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                    
                </form>
                
        @endif

        <div class="notification-author">

            <div class="circle" id="commentCircle">
                <a href="{{ route('profile.show', $notification->userNotification->id) }}" style="cursor: pointer;">
                    <img src="{{ asset('storage/profile_pictures/' . $notification->userNotification->profile_picture_id) }}" alt="{{$notification->userNotification->username}}">
                </a>
            </div>

            <div class="notification-content">
                <h4>
                    <a>{{$notification->content}}</a>
                </h4>
            </div>

        </div>

        <div class="notification-meta">

            <p>{{\Carbon\Carbon::parse($notification->created_at)->format('Y-m-d H:i')}} </p>

        </div>

    </div>

</div>
