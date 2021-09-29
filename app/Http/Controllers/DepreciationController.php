<?php

namespace App\Http\Controllers;

use App\Models\Depreciation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepreciationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->cant('viewAny', Depreciation::class)) {
            return redirect(route('errors.forbidden', ['area', 'Depreciation', 'view']));
        }

        $depreciation  = Depreciation::all();
        return view('depreciation.view', compact('depreciation'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->cant('create', Depreciation::class)) {
            return redirect(route('errors.forbidden', ['area', 'Depreciation', 'create']));
        }
        Depreciation::create($request->only('name', 'years'))->save();
        session()->flash('success_message', $request->name.' has been added to the system');
        return redirect(route('depreciation.index'));
    }

    public function show(Depreciation $depreciation)
    {
        return $depreciation->models;
    }

    public function update(Request $request, Depreciation $depreciation)
    {
        if (auth()->user()->cant('update', $depreciation)) {
            return redirect(route('errors.forbidden', ['area', 'Depreciation', 'update']));
        }

        $depreciation->fill($request->only('name', 'years'))->save();
        session()->flash('success_message', $request->name.' has been updated to the system');
        return redirect(route('depreciation.index'));
    }

   
    public function destroy(Depreciation $depreciation)
    {
        if (auth()->user()->cant('delete', Depreciation::class)) {
            return redirect(route('errors.forbidden', ['area', 'Depreciation', 'view']));
        }

        $name=$depreciation->name;
        $depreciation->delete();
        session()->flash('danger_message', $name . ' was deleted from the system');
        return redirect(route('depreciation.index'));
    }
}
