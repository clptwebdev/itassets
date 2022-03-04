<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller {

    public function index()
    {
        if(auth()->user()->cant('viewAny', Category::class))
        {
            return ErrorController::forbidden(route('dashboard'), 'Unauthorised to View Categories.');

        }
        $locations = auth()->user()->locations;

        $categories = Category::all();

        /* $categories = $categories->map(function($item){
            $item-
        }); */

        return view('category.view', compact('categories', 'locations'));
    }

    public function store(Request $request)
    {
        if(auth()->user()->cant('create', Category::class))
        {
            return ErrorController::forbidden(route('category.index'), 'Unauthorised to Store Categories.');

        }

        $validated = $request->validate([
            'name' => 'required',
        ]);

        Category::create(['name' => $request->name]);
        session()->flash('success_message', $request->name . ' has been successfully created');

        return redirect(route('category.index'));
    }

    public function update(Request $request, Category $category)
    {
        if(auth()->user()->cant('update', $category))
        {
            return ErrorController::forbidden(route('category.index'), 'Unauthorised to Edit Categories.');

        }
        $validated = $request->validate(['name' => 'required']);
        $category->name = $request->name;
        $category->save();
        session()->flash('success_message', $request->name . ' has been successfully created');

        return redirect(route('category.index'));
    }

    public function destroy(Category $category)
    {
        if(auth()->user()->cant('delete', $category))
        {
            return ErrorController::forbidden(route('category.index'), 'Unauthorised to Delete Categories.');

        }

        $name = $category->name;
        $category->delete();
        session()->flash('danger_message', $name . ' has been successfully deleted from the system');

        return redirect(route('category.index'));
    }

    public function search(Request $request)
    {
        $categories = Category::where('name', 'LIKE', '%' . $request->search . "%")->take(3)->get()->unique('name');
        $output = "<ul id='categorySelect' class='list-group'>";
        foreach($categories as $category)
        {
            $output .= " <li class='list-group-item d-flex justify-content-between align-items-center pointer' data-id='" . $category->id . "' data-name='" . $category->name . "'>
                            {$category->name}
                            <span class='badge badge-primary badge-pill'>1</span>
                        </li>";
        }
        $output .= "</ul>";

        return Response($output);
    }

}
