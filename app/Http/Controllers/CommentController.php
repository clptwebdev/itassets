<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Consumable;
use App\Models\Field;
use App\Models\Fieldset;
use App\Models\User;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Comment $comment)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //not needed
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        return view('comments.edit', [
            "comment" => $comment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Comment      $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            "title" => "required|max:255",
            "comment" => "nullable",
        ]);
        $comment->fill(array_merge($request->only('title', 'comment', 'user_id'
        ), ['user_id' => auth()->user()->id]))->save();

        session()->flash('success_message', request("title") . ' has been updated successfully');
        return redirect("/");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $name = $comment->title;
        $comment->delete();
        session()->flash('danger_message', 'The Comment - '.$name . ' was deleted from the system');

        return back();
    }

}
