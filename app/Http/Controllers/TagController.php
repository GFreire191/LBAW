<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Question;
use Illuminate\Http\Request;


class TagController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('admin');

        return view('partials.createTag');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('admin');

        $request->validate([
            'name' => 'required|max:255',
        ]);

        $tag = new Tag();
        $tag->name = $request->name;
        $tag->save();

        return response()->json([
            'tag' => $tag
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($questionId)
    {
        $question = Question::find($questionId);

        // get the tags that are not already attached to the question
        $tags = Tag::whereDoesntHave('questions', function ($query) use ($questionId) {
            $query->where('question_id', $questionId);
        })->get();

        return view('partials.addTagMenu', ['tags' => $tags, 'question' => $question]);
    }
    
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        //
    }

    public function delete(Tag $tag)
    {
        $this->authorize('admin');

        $tag->delete();

        
    }
}
