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

    public function index()
    {
        abort(404);
    }

    public function update(Request $request, Comment $comment)
    {
        if(auth()->user()->cant('update', $comment))
        {
            return ErrorController::forbidden(redirect()->back(), 'Unauthorised to Update Comments.');

        }

        $request->validate([
            "title" => "required|max:255",
            "comment" => "nullable",
        ]);
        $comment->fill(array_merge($request->only('title', 'comment', 'user_id'
        ), ['user_id' => auth()->user()->id]))->save();

        session()->flash('success_message', request("title") . ' has been updated successfully');

        return redirect()->back();
    }

    public function destroy(Comment $comment)
    {
        if(auth()->user()->cant('delete', $comment))
        {
            return ErrorController::forbidden(route('dashboard'), 'Unauthorised to Delete Comments.');

        }

        $name = $comment->title;
        $comment->delete();
        session()->flash('danger_message', 'The Comment - ' . $name . ' was deleted from the system');

        return back();
    }

}
