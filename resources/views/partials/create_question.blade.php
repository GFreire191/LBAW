





    
        <form id="question-form" action="/question" method="POST">
    
            @csrf
    
            <div class="form-group">
    
                <label for="title">Title</label>
    
                <input type="text" class="form-control input-fields question-author" id="title" name="title" placeholder="Enter title" required>
    
            </div>
    
            <div class="form-group">
    
                <label for="content">Content</label>
    
                <textarea class="form-control text-area-field input-fields" id="question-create-content" name="content" rows="3" required maxlength="255"></textarea>
    
            </div>
    
            <button type="submit" class="question-button">Submit</button>
    
        </form>





