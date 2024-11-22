<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Question;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $searchQuery = $request->input('query');
        
        $questions = Question::whereRaw("tsvectors @@ phraseto_tsquery('english', ?)", [$searchQuery])->get();

        return view('pages.search', ['questions' => $questions]);
    }
}
