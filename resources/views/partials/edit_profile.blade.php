<form id="profile-form" action="/edit" method="POST">

    @csrf
    <div class="form-group">
        <div class="circle" id="answerCreateCircle">
                <img src="{{ asset('storage/profile_pictures/' . auth()->user()->profile_picture_id) }}" alt="{{auth()->user()->username}}">
        </div>

        <div class="form-group">

            <label for="content">Username </label>

            <textarea class="form-control text-area-field input-fields" id="profile-edit-username" name="username_content" rows="1" required>{{Auth::user()->username}}</textarea>
        </div>

        <div class="form-group">    
            
            <label for="content">User Bio </label>

            <textarea class="form-control text-area-field input-fields" id="profile-edit-bio" name="bio_content" rows="2" required>{{Auth::user()->bio}}</textarea>

        </div>
    </div>

    <button type="submit" class="question-button" id="submitComment">Submit</button>

</form>