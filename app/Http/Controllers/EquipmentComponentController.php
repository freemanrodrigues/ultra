<?php

namespace App\Http\Controllers;

use App\Models\EquipmentComponent;
use Illuminate\Http\{Request,RedirectResponse};
use Illuminate\View\View;

class EquipmentComponentController
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
        dd('create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd('store'.$request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(EquipmentComponent $equipmentComponent)
    {
        dd('show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EquipmentComponent $equipmentComponent)
    {
        dd('edit');
    }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EquipmentComponent $equipmentComponent)
    {
        dd('updae'.$request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EquipmentComponent $equipmentComponent)
    {
        //
    }
}
