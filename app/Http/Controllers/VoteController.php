<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Question;
use App\Models\Answer;

class VoteController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Vote $vote)
    {   
        $this->authorize('store', Vote::class);
        $vote->save();
        $vote = $vote->fresh();
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Vote $vote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vote $vote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Vote $vote)
    {
        $this->authorize('update', Vote::class);
        
        if ($vote->vote_status === 'up'){
            $vote->vote_status = 'down';
        }
        elseif ($vote->vote_status === 'down'){
            $vote->vote_status = 'up';
        }
        $vote->save();
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Vote $vote)
    {
        $this->authorize('delete', Vote::class);

        $vote->delete();
        
    }
    

    public function vote_question(Request $request, Question $question)
    {
        $this->authorize('vote_question', Vote::class);

        $user = Auth::user()->id;
        $vote = Vote::where('user_id', $user)
             ->where('parent_id', $question->id)
             ->where('parent_type', 'App\Models\Question')
             ->first();
        $vote_status= $request->input('vote_status');

        if($vote){
            
            if($vote_status === 'up' && $vote-> vote_status == 'up'){
                $vote->delete($vote);
                return response()->json(['numberVotes' => '-1', 'updateFlag' => '0']);
            }
            elseif($vote_status === 'down' && $vote-> vote_status == 'up'){
                $this->update($vote);
                return response()->json(['numberVotes' => '1', 'updateFlag' => '1']);
            }
            elseif($vote_status === 'up' && $vote-> vote_status == 'down'){
                $this->update($vote);
                return response()->json(['numberVotes' => '1', 'updateFlag' => '1']);
            }
            elseif($vote_status === 'down' && $vote-> vote_status == 'down'){
                $vote->delete($vote);
                return response()->json(['numberVotes' => '-1', 'updateFlag' => '0']);
            }
        }
        else{
            
            $new_vote = new Vote();
            $new_vote->parent_id = $question->id;
            $new_vote->user_id = $user;
            $new_vote->parent_type = 'App\Models\Question';
            if($vote_status === 'up'){
                $new_vote->vote_status = 'up';
            }
            elseif($vote_status === 'down'){
                $new_vote->vote_status = 'down';
            }
            $this->store($new_vote);
            return response()->json(['numberVotes' => '1', 'updateFlag' => '0']);
        }
       
    }
    
    public function vote_answer(Request $request, Answer $answer)
    {
        $this->authorize('vote_answer', Vote::class);

        $user = Auth::user()->id;
        $vote = Vote::where('user_id', $user)
             ->where('parent_id', $answer->id)
             ->where('parent_type', 'App\Models\Answer')
             ->first();
        $vote_status= $request->input('vote_status');

        if($vote){
            
            if($vote_status === 'up' && $vote-> vote_status == 'up'){
                $vote->delete($vote);
                return response()->json(['numberVotes' => '-1', 'updateFlag' => '0']);
            }
            elseif($vote_status === 'down' && $vote-> vote_status == 'up'){
                $this->update($vote);
                return response()->json(['numberVotes' => '1', 'updateFlag' => '1']);
            }
            elseif($vote_status === 'up' && $vote-> vote_status == 'down'){
                $this->update($vote);
                return response()->json(['numberVotes' => '1', 'updateFlag' => '1']);
            }
            elseif($vote_status === 'down' && $vote-> vote_status == 'down'){
                $vote->delete($vote);
                return response()->json(['numberVotes' => '-1', 'updateFlag' => '0']);
            }
        }
        else{
            
            $new_vote = new Vote();
            $new_vote->parent_id = $answer->id;
            $new_vote->user_id = $user;
            $new_vote->parent_type = 'App\Models\Answer';
            if($vote_status === 'up'){
                $new_vote->vote_status = 'up';
            }
            elseif($vote_status === 'down'){
                $new_vote->vote_status = 'down';
            }
            $this->store($new_vote);
            return response()->json(['numberVotes' => '1', 'updateFlag' => '0']);
        }
       
    }
        
}
        