<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\Tag;

class HomeController extends Controller
{
    public function redirect()
    {
        return redirect('/home');
    }

    public function show_about()
    {
        return view('pages.about');
    }

    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $tagId = $request->input('tag');
    
        if ($tagId) {
            $tag = Tag::find($tagId);
            $questions = $tag->questions()->orderBy('created_at', 'desc')->orderBy('id', 'desc')->skip(($page - 1) * 6)->take(6)->get();
        } else {
            $questions = Question::orderBy('created_at', 'desc')->orderBy('id', 'desc')->skip(($page - 1) * 6)->take(6)->get();
        }
    
        $tags = Tag::getTags();
    
        if ($request->ajax()) {
            return view('partials.questionsList', compact('questions'))->render();
        } else {
            return view('pages.home', ['questions' => $questions, 'tags' => $tags]);
        }
    }


}
