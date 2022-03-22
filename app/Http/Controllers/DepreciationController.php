<?php

namespace App\Http\Controllers;

use App\Models\Depreciation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepreciationController extends Controller {

    public function index()
    {
        if(auth()->user()->cant('viewAny', Depreciation::class))
        {
            return ErrorController::forbidden(to_route('dashboard'), 'Unauthorised to View Depreciation.');

        }

        $depreciation = Depreciation::paginate();

        return view('depreciation.view', compact('depreciation'));
    }

    public function store(Request $request)
    {
        if(auth()->user()->cant('create', Depreciation::class))
        {
            return ErrorController::forbidden(to_route('depreciation.index'), 'Unauthorised to Create Depreciation.');

        }
        Depreciation::create($request->only('name', 'years'))->save();
        session()->flash('success_message', $request->name . ' has been added to the system');

        return to_route('depreciation.index');
    }

    public function show(Depreciation $depreciation)
    {
        return $depreciation->models;
    }

    public function update(Request $request, Depreciation $depreciation)
    {
        if(auth()->user()->cant('update', $depreciation))
        {
            return ErrorController::forbidden(to_route('depreciation.index'), 'Unauthorised to Update Depreciation.');

        }

        $depreciation->fill($request->only('name', 'years'))->save();
        session()->flash('success_message', $request->name . ' has been updated to the system');

        return to_route('depreciation.index');
    }

    public function destroy(Depreciation $depreciation)
    {
        if(auth()->user()->cant('delete', Depreciation::class))
        {
            return ErrorController::forbidden(to_route('depreciation.index'), 'Unauthorised to Delete Depreciation.');

        }

        $name = $depreciation->name;
        $depreciation->delete();
        session()->flash('danger_message', $name . ' was deleted from the system');

        return to_route('depreciation.index');
    }

}
