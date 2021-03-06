<?php

namespace App\Http\Controllers;

use App\Models\Fieldset;
use App\Models\Field;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FieldsetController extends Controller {

    public function index()
    {
        if(auth()->user()->cant('viewAny', Fieldset::class))
        {
            return ErrorController::forbidden(route('dashboard'), 'Unauthorised to View FieldSets.');

        }

        $fieldsets = Fieldset::paginate();

        return view('fieldsets.view', compact('fieldsets'));
    }

    public function create()
    {
        if(auth()->user()->cant('create', Fieldset::class))
        {
            return ErrorController::forbidden(route('fieldsets.index'), 'Unauthorised to Create FieldSets.');

        }

        $fields = \App\Models\Field::all();

        return view('fieldsets.create', compact('fields'));
    }

    public function store(Request $request)
    {
        if(auth()->user()->cant('create', Fieldset::class))
        {
            return ErrorController::forbidden(route('fieldsets.index'), 'Unauthorised to Create FieldSets.');

        }

        $validated = $request->validate([
            'name' => 'required',
        ]);

        $fieldset = Fieldset::create(['name' => $request->name]);
        $array = explode(',', $request->fields);
        $fieldset->fields()->attach($array);
        session()->flash('success_message', 'The ' . $fieldset->name . ' field was created Successfully.');

        return to_route('fieldsets.index');
    }

    public function edit(Fieldset $fieldset)
    {
        if(auth()->user()->cant('update', Fieldset::class))
        {
            return ErrorController::forbidden(route('fieldsets.index'), 'Unauthorised to Update FieldSets.');

        }
        $fields = Field::all();

        return view('fieldsets.edit', compact('fieldset', 'fields'));
    }

    public function update(Request $request, Fieldset $fieldset)
    {
        if(auth()->user()->cant('update', Fieldset::class))
        {
            return ErrorController::forbidden(route('fieldsets.index'), 'Unauthorised to Update FieldSets.');

        }

        $validated = $request->validate([
            'name' => 'required',
        ]);

        $fieldset->update(['name' => $request->name]);
        $array = explode(',', $request->fields);
        $fieldset->fields()->sync($array);

        session()->flash('success_message', 'The ' . $fieldset->name . ' field was updated Successfully.');

        return to_route('fieldsets.index');
    }

    public function destroy(Fieldset $fieldset)
    {
        if(auth()->user()->cant('delete', Fieldset::class))
        {
            return ErrorController::forbidden(route('fieldsets.index'), 'Unauthorised to Delete FieldSets.');

        }

        $name = $fieldset->name;
        $fieldset->fields()->detach();
        $fieldset->delete();
        session()->flash('danger_message', 'The ' . $name . ' field was deleted from the system');

        return to_route('fieldsets.index');
    }

}
