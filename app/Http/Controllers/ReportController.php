<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;




class ReportController extends Controller
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
        $this->authorize('create', Report::class);

        return view('partials.reportForm');
    }

    /**
     * Store a newly created resource in storage.
     */
    
     public function store(Request $request, $parentType, $parentId)
     {
         $this->authorize('create', Report::class);
     
         $request->validate([
             'motive' => 'required|max:255',
         ]);
     
         if($parentType == 'question'){
            $parentType = 'App\Models\Question';
         } else{
            $parentType = 'App\Models\Answer';
         }

     
         $report = new Report();
         $report->motive = $request->input('motive');
         $report->user_id = Auth::user()->id;
         $report->parent_id = $parentId;
         $report->parent_type = $parentType;
         $report->save();
     
         $report = $report->fresh();
     }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }

    public function delete(Report $report)
    {
        $this->authorize('admin');

        $report->delete();

        return;
    }
}
