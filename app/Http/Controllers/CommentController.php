<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show($type, $parent)
    {
        if ($type === 'question') {
            $parentObject = Question::findOrFail($parent);
        } elseif ($type === 'answer') {
            $parentObject = Answer::findOrFail($parent);
        } else {
            abort(404);
        }

        $comments = $parentObject->comments;

        return view('partials.commentList', ['comments' => $comments]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $parentType = $comment->parent_type;
        $parentId = $comment->parent_id;
        
        $comment->delete();

        return redirect()->back();
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);
        
        $comment->content = $request->input('commentContent');
        $comment->user_id = Auth::user()->id;
        $comment->edited = true;
        $comment->updated_at = now();
        $comment->save();    
    }

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
        $this->authorize('create', Comment::class);

        return view('partials.create_comment');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $parentType, $parentId)
    {
        $this->authorize('create', Comment::class);

        $comment = new Comment();
        $comment->content = $request->input('content');
        $comment->user_id = Auth::user()->id;

        if($parentType == 'question'){
            $comment->parent_type = 'App\Models\Question';
        }else if($parentType == 'answer'){
            $comment->parent_type = 'App\Models\Answer';
        }

        $comment->parent_id = $parentId;
        $comment->save();

        $comment = $comment->fresh();


        return view('partials.comment', ['comment' => $comment]);
    }
}