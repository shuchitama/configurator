<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModelController extends Controller
{
    public function uploadModel(Request $request)
    {
        if($request->hasFile('model')) {
            $modelFilename = $request->file('model')->getClientOriginalName();
            $request->file('model')->storeAs('models', $modelFilename, 'public');

            if($request->hasFile('bkgd')) {
                $bgFilename = $request->file('bkgd')->getClientOriginalName();
                $request->file('bkgd')->storeAs('hdr', $bgFilename, 'public');
            }
            return view('model', ['model' => $modelFilename, 'bg' => $bgFilename]);
        } return "not uploaded :(";
    }

    public function uploadThree(Request $request)
    {
        if($request->hasFile('model')) {
            $modelFilename = $request->file('model')->getClientOriginalName();
            $request->file('model')->storeAs('models', $modelFilename, 'public');
            return view('three', ['model' => $modelFilename]);
        } return "not uploaded :(";
    }
}