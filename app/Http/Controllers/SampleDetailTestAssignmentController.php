<?php

namespace App\Http\Controllers;

use App\Models\SampleDetailTestAssignment;
use Illuminate\Http\Request;

class SampleDetailTestAssignmentController
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SampleDetailTestAssignment $sampleDetailTestAssignment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SampleDetailTestAssignment $sampleDetailTestAssignment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SampleDetailTestAssignment $sampleDetailTestAssignment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SampleDetailTestAssignment $sampleDetailTestAssignment)
    {
        //
    }

    public function assginedTestForSample($id)
    {
      $test_list =  SampleDetailTestAssignment::getAssignedTestForSample($id);
     
     
      return view('test-assigned',compact('test_list' ));
    }
}
