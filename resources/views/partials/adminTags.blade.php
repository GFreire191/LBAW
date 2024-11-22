<div class="tag-select-container" id="admin-tag-select-container">
    <h2>Existent Tags:</h2>
    @foreach($tags as $tag)
        <a href="{{ route('delete.tag', ['tag' => $tag->id]) }}" class="tag-button-rem-admin tagButton" data-tag-id="{{$tag->id}}" >{{$tag->name}}</a>
        <form class="delete-tag-form" action="{{ route('delete.tag', ['tag' => $tag->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"></button>
        </form>

        
    @endforeach

    <a href="{{route('create.tag')}}" class="tagButton tagAdminAdd" > Add </a>
</div>

