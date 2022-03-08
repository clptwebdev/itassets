<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Http\Controllers\Controller;
use App\Models\Fieldset;
use Illuminate\Http\Request;

class FieldController extends Controller {

    public function index()
    {
        if(auth()->user()->cant('viewAny', Field::class))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised to View Fields.');

        }

        $fields = Field::all();

        return view('fields.view', compact('fields'));
    }

    public function create()
    {
        if(auth()->user()->cant('create', Field::class))
        {
            return ErrorController::forbidden(to_route('fields.index'), 'Unauthorised to Create Fields.');

        }

        return view('fields.create');
    }

    public function store(Request $request)
    {
        if(auth()->user()->cant('create', Field::class))
        {
            return ErrorController::forbidden(to_route('fields.index'), 'Unauthorised to Create Fields.');

        }

        Field::create($request->only('name', 'format', 'type', 'required', 'value', 'help'))->save();
        session()->flash('sucess_message', $request->name . ' has been added to the system');

        return to_route('fields.index');
    }

    public function edit(Field $field)
    {
        if(auth()->user()->cant('update', Field::class))
        {
            return ErrorController::forbidden(to_route('fields.index'), 'Unauthorised to Update Fields.');

        }

        return view('fields.edit', compact('field'));
    }

    public function update(Request $request, Field $field)
    {
        if(auth()->user()->cant('update', Field::class))
        {
            return ErrorController::forbidden(to_route('fields.index'), 'Unauthorised to Update Fields.');

        }
        $field->fill($request->only('name', 'format', 'type', 'required', 'value', 'help'))->save();
        session()->flash('sucess_message', $request->name . ' has been updated on the system');

        return to_route('fields.index');
    }

    public function destroy(Field $field)
    {
        if(auth()->user()->cant('delete', Field::class))
        {
            return ErrorController::forbidden(to_route('fields.index'), 'Unauthorised to Delete Fields.');
        }
        $name = $field->name;
        $field->delete();
        session()->flash('danger_message', 'The ' . $name . ' field was deleted from the system');

        return to_route('fields.index');
    }

}
