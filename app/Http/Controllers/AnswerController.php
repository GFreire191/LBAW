<?php 

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;


class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Question $question)
    {
        try {
            $this->authorize('create', Answer::class);
    
            $answer = new Answer();
            $answer->content = $request->input('content');
            $answer->user_id = Auth::user()->id;
            $answer->question_id = $question->id;
            $answer->save();
    
            $answer = $answer->fresh();
    
            return view('partials.answer', ['answer' => $answer, 'question' => $question]);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json(['error' => 'User is not authorized'], 403);
        }

        
    }

    /**
     * Display the specified resource.
     */
    public function show(Answer $answer)
    {
        $this->authorize('show', $answer);

        return view('pages.question', compact('answer'));
    }


    public function edit_form(Answer $answer)
    {
        
        $this->authorize('update', $answer);
        
    }


    public function update(Request $request, Answer $answer)
    {
        if($request->has('correct')) {
            $this->authorize('markCorrect', $answer);
            if($request->input('correct') == "true") {
                $answer->correct = 1;
            } else {
                $answer->correct = 0;
            }
            $answer->save();
            return response()->json(['message' => 'Answer marked as correct']);
        } 

        $this->authorize('update', $answer);
        
        $answer->content = $request->input('content');
        $answer->user_id = Auth::user()->id;
        $answer->save();
        return redirect('/home');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Answer $answer)
    {
        $this->authorize('delete', $answer);

        $reports = Report::where('parent_id', $answer->id)
                        ->where('parent_type', 'App\Models\Answer')
                        ->get();
        foreach ($reports as $report) {
            $report->delete();
        }

        $answer->delete();

        return redirect()->back();
    }

}
