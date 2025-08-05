<?php

namespace App\Http\Controllers;

use App\Models\EquipmentAssignment;
use Illuminate\Http\{Request,RedirectResponse};
use Illuminate\View\View;

class EquipmentAssignmentController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        dd('Index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        dd('Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd('Store'.$request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(EquipmentAssignment $equipmentAssignment)
    {
        dd('show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EquipmentAssignment $equipmentAssignment)
    {
        dd('edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EquipmentAssignment $equipmentAssignment)
    {
        dd('update'.$request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EquipmentAssignment $equipmentAssignment)
    {
        //
    }
}
