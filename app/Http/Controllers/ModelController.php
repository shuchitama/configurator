<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModelController extends Controller
{
    public function uploadModel(Request $request)
    {
        if($request->hasFile('model')) {
            // $path = $request->file('model')->store('models');

            // return $path;
            $filename = $request->file('model')->getClientOriginalName();
            // if(auth()->user()->avatar) {
            //     Storage::delete('/public/models/'.auth()->user()->avatar);
            // };
            $request->file('model')->storeAs('models', $filename, 'public');
            return "upload successful";
        } return "not uploaded :(";
    }
}