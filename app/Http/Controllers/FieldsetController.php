<?php

namespace App\Http\Controllers;

use App\Models\Fieldset;
use App\Models\Field;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FieldsetController extends Controller
{

    public function index()
    {
        if (auth()->user()->cant('viewAny', Fieldset::class)) {
            return redirect(route('errors.forbidden', ['area', 'fieldset', 'view']));
        }

        $fieldsets = Fieldset::all();
        return view('fieldsets.view', compact('fieldsets'));
    }

    public function create()
    {
        if (auth()->user()->cant('create', Fieldset::class)) {
            return redirect(route('errors.forbidden', ['area', 'fieldset', 'create']));
        }

        $fields = \App\Models\Field::all();
        return view('fieldsets.create', compact('fields'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->cant('create', Fieldset::class)) {
            return redirect(route('errors.forbidden', ['area', 'fieldset', 'create']));
        }

        $validated = $request->validate([
            'name' => 'required',
        ]);

        $fieldset = Fieldset::create(['name' => $request->name]);
        $array = explode(',', $request->fields);
        $fieldset->fields()->attach($array);
        return redirect(route('fieldsets.index'));
    }

    public function edit(Fieldset $fieldset)
    {
        if (auth()->user()->cant('update', Fieldset::class)) {
            return redirect(route('errors.forbidden', ['area', 'Category', 'edit']));
        }

        $fields = Field::all();
        return view('fieldsets.edit', compact('fieldset', 'fields'));
    }

    public function update(Request $request, Fieldset $fieldset)
    {
        if (auth()->user()->cant('update', Fieldset::class)) {
            return redirect(route('errors.forbidden', ['area', 'Category', 'update']));
        }

        $validated = $request->validate([
            'name' => 'required',
        ]);

        $fieldset->update(['name' => $request->name]);
        $array = explode(',', $request->fields);
        $fieldset->fields()->sync($array);
        return redirect(route('fieldsets.index'));
    }

    public function destroy(Fieldset $fieldset)
    {
        if (auth()->user()->cant('delete', Fieldset::class)) {
            return redirect(route('errors.forbidden', ['area', 'Category', 'delete']));
        }

        $name=$fieldset->name;
        $fieldset->fields()->detach();
        $fieldset->delete();
        session()->flash('danger_message', 'The ' . $name . ' field was deleted from the system');
        return redirect(route('fieldsets.index'));
    }
}
