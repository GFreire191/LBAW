<form id="report-form" action="" method="POST">
    
            @csrf
    
            <div class="form-group">
    
                <label for="content">Motive: </label>
    
                <textarea class="form-control text-area-field input-fields" id="question-create-content" name="motive" rows="3" required maxlength="255"></textarea>
    
            </div>
    
            <button type="submit" class="question-button">Report</button>
    
</form>

