<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;

class StateController
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
    public function show(State $state)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(State $state)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, State $state)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(State $state)
    {
        //
    }

    public function getState(Request $request)
    {
        $request->validate([
            'state' => 'required|integer',
        ]);
       
        $state = State::where('id', $request->state)->first();

        if ($state) {
            return response()->json([
                'exists' => true,
                'state_id' => $state->id, 
                'state_code' => $state->shortname, 
            ]);
        }
        return response()->json(['exists' => false]);
    }
}
