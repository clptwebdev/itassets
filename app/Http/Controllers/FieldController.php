<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FieldController extends Controller
{
     public function index()
    {
        $fields = Field::all();
        return view('fields.view', compact('fields'));
    }

    public function create(){
        return view('fields.create');
    }

    public function store(Request $request)
    {
        Field::create($request->only('name', 'format', 'type', 'required', 'value', 'help'))->save();
        session()->flash('sucess_message', $request->name.' has been added to the system');
        return redirect(route('fields.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Field  $fields
     * @return \Illuminate\Http\Response
     */
    public function show(Field $fields)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fields  $fields
     * @return \Illuminate\Http\Response
     */
    public function edit(Field $field)
    {
        return view('fields.edit', compact('field'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Field  $fields
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Field $field)
    {
        $field->fill($request->only('name', 'format', 'type', 'required', 'value', 'help'))->save();
        session()->flash('sucess_message', $request->name.' has been updated on the system');
        return redirect(route('fields.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Field  $fields
     * @return \Illuminate\Http\Response
     */
    public function destroy(Field $field)
    {
        $name= $field->name;
        $field->delete();
        session()->flash('danger_message', 'The '.$name. ' field was deleted from the system');
        return redirect(route('fields.index'));
    }
}
