<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserController extends Controller
{
    
    public function showProfile($id)
    {

        $user = User::find($id);

        $questions = $user->questions()->orderBy('created_at', 'desc')->get();
        $answers = $user->answers()->orderBy('created_at', 'desc')->get();

        return view('pages.profile', ['questions' => $questions, 'answers' => $answers, 'user' => $user]);
    }

    public function followQuestion(Question $question)
    {
        try {

            $this->authorize('follow', $question);
            $user = Auth::user();

            $user->followedQuestions()->attach($question->id);

            return;
        } catch (AuthorizationException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }

        
    }

    public function unfollowQuestion(Question $question)
    {
        $this->authorize('unfollow', $question);

        $user = Auth::user();

        $user->followedQuestions()->detach($question->id);

        return;
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
        $this->authorize('create', User::class);
        //check this
        return view('partials.edit_profile');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $user_id)
    {
        $this->authorize('store', User::class);

        $user = User::where('id' , $user_id)->first();
        $user->username = $request->input('contentUsername');
        $user->bio = $request->input('contentBio');
        $user->save();

        $user = $user->fresh();

        $questions = $user->questions()->orderBy('created_at', 'desc')->get();
        $answers = $user->answers()->orderBy('created_at', 'desc')->get();

        return view('pages.profile', ['questions' => $questions, 'answers' => $answers, 'user' => $user]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
