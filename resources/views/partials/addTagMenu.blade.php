








<div class="tag-select-container">
    
    <h2>Select the tags to add:</h2>
    @foreach($tags as $tag)
        <span class="tagButtonAdd"  data-question-id="{{ $question->id }}" data-tag-id="{{$tag->id}}">{{$tag->name}}</span>
    @endforeach





</div>

