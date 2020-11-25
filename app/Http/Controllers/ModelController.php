<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModelController extends Controller
{
    public function uploadModel(Request $request)
    {
        if($request->hasFile('model')) {
            return "Got a file!";
            // $filename = $request->model->getClientOriginalName();
            // if(auth()->user()->avatar) {
            //     Storage::delete('/public/models/'.auth()->user()->avatar);
            // };
            // $request->model->storeAs('models', $filename, 'public');
        } return "not uploaded :(";
    }
}