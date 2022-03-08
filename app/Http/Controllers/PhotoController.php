<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PhotoController extends Controller {

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,svg|max:2048'
        ]);

        $uploadDir = asset('images');
        $response = [
            'status' => 0,
            'message' => 'Form submission failed, please try again.',
            'path' => 'NULL',
            'id' => 'NULL'
        ];

        $name = $request->name;

        // File path config
        $fileName = $request->file->getClientOriginalName();
        if($filePath = $request->file('file')->storeAs('images', $fileName, 'public'))
        {
            $photo = Photo::create(['name' => $name, 'path' => $filePath]);
            $response['status'] = 1;
            $response['message'] = 'Image was uploaded successfully';
            $response['path'] = $photo->path;
            $response['id'] = $photo->id;
        } else
        {
            $response['message'] = 'There was an error with the Upload!';
        }

        return response()->json($response);
    }

}
