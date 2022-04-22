<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller {

    public function upload(Request $request)
    {
        $request->validate([

            'file' => 'required|mimes:jpg,jpeg,png,svg|max:2048',

        ]);

        $uploadDir = asset('images');

        $response = [

            'status' => 0,

            'message' => 'Form submission failed, please try again.',

            'path' => 'NULL',

            'id' => 'NULL',

        ];

        $name = $request->name;

        // File path config

        $fileName = $request->file->getClientOriginalName();

        if($filePath = $request->file('file')->storeAs('images', $fileName, 'public'))
        {

            $photo = Photo::create(['name' => $name, 'path' => $filePath, 'user_id' => auth()->user()->id]);

            $response['status'] = 1;

            $response['message'] = 'Image was uploaded successfully';

            $response['path'] = asset($photo->path);

            $response['id'] = $photo->id;

        } else
        {

            $response['message'] = 'There was an error with the Upload!';

        }

        return response()->json($response);
    }

    public function getPhotos($page)
    {
        $limit = 51;
        $offset = $limit * ($page - 1);
        $photos = Photo::take($limit)->offset($offset)->get();
        $photoCount = Photo::count();
        $pages = round(ceil($photoCount / $limit), 0);

        //Get the amount of pages
//        $photos = Photo::all();
        $html = '<p>Select an image below:</p>';
        $html .= '<img src="' . asset('images/svg/location-image.svg') . '" width="80px" alt="Default Picture" onclick="selectPhoto(0, \"' . asset('images/svg/location-image.svg') . '\");">';

        foreach($photos as $photo)
        {
            $html .= '<img src="' . asset($photo->path) . '" width="80px" alt="' . $photo->name . '"onclick="selectPhoto(' . $photo->id . ', ' . asset($photo->path) . ')">';

        }
        $html .= '<nav aria-label="Page navigation example">
                  <ul class="pagination">';
        if($page > 1)
        {
            $html .= '<li class="page-item" ><a class="page-link" href = "#" onclick="getPhotoPage(' . $page - 1 . ')" > Previous</a ></li >';
        }

        if($page - 1 > 1)
        {
            $html .= '<li class="page-item" ><a class="page-link" href = "#" onclick="getPhotoPage(' . $page - 1 . ')" >' . $page - 1 . '</a ></li >';
        }

        $html .= '<li class="page-item active" ><a class="page-link" href = "#" >' . $page . '</a ></li >';

        if($page + 1 < $pages)
        {
            $html .= '<li class="page-item" ><a class="page-link" href = "#" onclick="getPhotoPage(' . $page + 1 . ')">' . $page++ . '</a ></li >';
        }

        if($page + 1 < $pages)
        {
            $html .= '<li class="page-item" ><a class="page-link" href = "#" onclick="getPhotoPage(' . $page + 1 . ')"> Next</a ></li >';
        }

        $html .= '</ul>
                </nav>';

//            <div class='m-3'>
        //bootstrap style button
//            onclick="getPhotoPages(4);"
//            </div>

        return $html;
    }

}
