<?php

namespace App\Http\Controllers;

use App\Models\Fieldset;
use App\Models\Field;
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
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $fieldset = Fieldset::create(['name' => $request->name]);
        $array = explode(',', $request->fields);
        $fieldset->fields()->attach($array);
        return redirect(route('fieldsets.index'));
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
        $fields = Field::all();
        return view('fieldsets.edit', compact('fieldset', 'fields'));
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
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $fieldset->update(['name' => $request->name]);
        $array = explode(',', $request->fields);
        $fieldset->fields()->sync($array);
        return redirect(route('fieldsets.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fieldset  $fieldset
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fieldset $fieldset)
    {
        $name=$fieldset->name;
        $fieldset->fields()->detach();
        $fieldset->delete();
        session()->flash('danger_message', 'The ' . $name . ' field was deleted from the system');
        return redirect(route('fieldsets.index'));
    }
}
