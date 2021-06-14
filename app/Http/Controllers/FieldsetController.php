<?php

namespace App\Http\Controllers;

use App\Models\Fieldset;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FieldsetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fieldsets = Fieldset::all();
        return view('fieldsets.view', compact('fieldsets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fields = \App\Models\Field::all();
        return view('fieldsets.create', compact('fields'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fieldset  $fieldset
     * @return \Illuminate\Http\Response
     */
    public function show(Fieldset $fieldset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fieldset  $fieldset
     * @return \Illuminate\Http\Response
     */
    public function edit(Fieldset $fieldset)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fieldset  $fieldset
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fieldset $fieldset)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fieldset  $fieldset
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fieldset $fieldset)
    {
        //
    }
}
