<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    

    public function index()
    {
        if (auth()->user()->cant('viewAny', Category::class)) {
            return redirect(route('errors.forbidden', ['area', 'Category', 'view']));
        }

        if(auth()->user()->role_id == 1){
            $locations = \App\Models\Location::all();
        }else{
            $locations = auth()->user()->locations;
        }
        return view('category.view', compact('locations'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->cant('create', Category::class)) {
            return redirect(route('errors.forbidden', ['area', 'Category', 'delete']));
        }

        $validated = $request->validate([
            'name' => 'required'
        ]);

        Category::create(['name'=> $request->name]);
        session()->flash('success_message', $request->name.' has been successfully created');
        return redirect(route('category.index'));
    }

    public function update(Request $request, Category $category)
    {
        if (auth()->user()->cant('update', $category)) {
            return redirect(route('errors.forbidden', ['category', $category->id, 'update']));
        }
        $validated = $request->validate(['name' => 'required']);
        $category->name = $request->name;
        $category->save();
        session()->flash('success_message', $request->name.' has been successfully created');
        return redirect(route('category.index'));
    }

    public function destroy(Category $category)
    {
        if (auth()->user()->cant('delete', $category)) {
            return redirect(route('errors.forbidden', ['category', $category->id, 'delete']));
        }

        $name = $category->name;
        $category->delete();
        session()->flash('danger_message', $name.' has been successfully deleted from the system');
        return redirect(route('category.index'));
    }
}
