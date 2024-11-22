
    

  
    
          
            <div class="circle" id="answerCreateCircle">
            <img src="{{ asset('storage/profile_pictures/' . auth()->user()->profile_picture_id) }}" alt="{{auth()->user()->username}}">
            </div>


        


        <form id="answer-form" action="{{ route('store.answer', ['question' => $question->id]) }}" method="POST">    
            @csrf
    
        
    
    
                <textarea class="form-control text-area-field input-fields" id="answer-create-content" name="content" rows="3" required></textarea>
    
            
    
            <button type="submit" class="question-button" id="createAnswerButton">Answer</button>
        </form>
            
          

  
















        