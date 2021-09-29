<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FieldController extends Controller
{
     public function index()
    {
        if (auth()->user()->cant('viewAny', Fieldset::class)) {
            return redirect(route('errors.forbidden', ['area', 'fieldset', 'view']));
        }

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

    public function edit(Field $field)
    {
        return view('fields.edit', compact('field'));
    }

    public function update(Request $request, Field $field)
    {
        $field->fill($request->only('name', 'format', 'type', 'required', 'value', 'help'))->save();
        session()->flash('sucess_message', $request->name.' has been updated on the system');
        return redirect(route('fields.index'));
    }

    public function destroy(Field $field)
    {
        $name= $field->name;
        $field->delete();
        session()->flash('danger_message', 'The '.$name. ' field was deleted from the system');
        return redirect(route('fields.index'));
    }
}
