<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function upload(Request $request){
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
        if($filePath = $request->file('file')->storeAs('images', $fileName, 'public')){
            $photo = Photo::create(['name'=> $name, 'path' => $filePath]);
            $response['status'] = 1; 
            $response['message'] = 'Image was uploaded successfully'; 
            $response['path'] = $photo->path;
            $response['id'] = $photo->id;
        }else{
            $response['message'] = 'There was an error with the Upload!'; 
        }
        
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
