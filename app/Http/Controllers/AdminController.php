<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Question;
use App\Models\Tag;


class AdminController extends Controller
{
    public function reports()
{
    $this->authorize('admin');

    $reports = Report::with(['user', 'parent'])->get();
    $reports = $reports->sortBy('created_at');

    return view('pages.admin', [
        'reports' => $reports,
        'tags' => Tag::all()
    ]);
}

    public function tags()
    {
        $this->authorize('admin');

        $tags = Tag::all();

        return view('partials.adminTags', [
            'tags' => $tags
        ]);

}
}