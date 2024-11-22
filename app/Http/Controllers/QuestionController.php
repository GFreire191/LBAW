<?php 

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag;
use App\Models\Report;
use App\Models\Vote;



class QuestionController extends Controller
{

    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Question::class);

        return view('partials.create_question');
    }
 
    
    public function store(Request $request)
{
    $this->authorize('create', Question::class);

    $question = new Question();
    $question->title = $request->input('title');
    $question->content = $request->input('content');
    $question->user_id = Auth::user()->id;
    $question->save();

    $question = $question->fresh();


    return view('partials.question', ['question' => $question]);
}

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Question $question)
{
    $this->authorize('show', $question);
    $page = $request->input('page', 1);
    $perPage = 6;

    //fetch the vote from the user regarding that question 
    if (Auth::check()) {
        $question_vote = $question->votes()->where('user_id',Auth::user()->id)->first();
    }

    // Fetch all answers and order them by 'correct' and 'created_at'
    $answers = $question->answers()->orderBy('correct', 'desc')->orderBy('created_at', 'desc')->paginate($perPage);

    $tags = $question->tags()->get();

    if ($request->ajax()) {
        return view('partials.answersList', ['answers' => $answers])->render();
    }
    else{
        return view('pages.question', ['question'=>$question,'answers'=>$answers, 'tags'=>$tags]);
    }
}

    public function show_top()
    {
    
        $questions = Question::select('*')
                                ->orderByRaw('(up_votes + down_votes) DESC')
                                ->get();

        $tags = Tag::getTags();

        return view('pages.home', ['questions' => $questions, 'tags' => $tags]);
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit_form(Question $question)
    {
        
        $this->authorize('update', $question);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        if (request()->has('deleteTag')){
            $question->tags()->detach($request->input('tagId'));

            return response()->json(['message' => 'Tag deleted successfully']);
        } else if (request()->has('addTag')){
            $question->tags()->attach($request->input('tagId'));

            return response()->json(['message' => 'Tag added successfully']);
        }
        $this->authorize('update', $question);
        
        $question->title = $request->input('title');
        $question->content = $request->input('content');
        $question->user_id = Auth::user()->id;
        $question->edited = true;
        $question->updated_at = now();
        $question->save();
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Question $question)
    {

        $this->authorize('delete', $question);

        // Delete all associated reports
        $reports = Report::where('parent_id', $question->id)
        ->where('parent_type', 'App\Models\Answer')
        ->get();
        foreach ($reports as $report) {
            $report->delete();
        }

        
        $question->delete();

        
        return redirect()->back();
    }
    


    public function get_author($id)
    {
        $question = Question::find($id);
        $user = $question->user;
        return $user -> username;
    }

    
    
    
}
