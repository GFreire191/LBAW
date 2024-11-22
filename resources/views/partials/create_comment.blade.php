<form id="comment-form" action="/comment" method="POST">

    @csrf
    
    <div class="form-group">

        <div class="circle" id="answerCreateCircle">
            <img src="{{ asset('storage/profile_pictures/' . auth()->user()->profile_picture_id) }}" alt="{{auth()->user()->username}}">
        </div>

        <label for="content">Comment Content</label>

        <textarea class="form-control text-area-field input-fields" id="comment-create-content" name="content" rows="3" required></textarea>

    </div>

    <button type="submit" class="question-button" id="submitComment">Submit</button>

</form>