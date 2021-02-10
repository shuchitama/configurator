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
                $request->file('bkgd')->storeAs('backgrounds', $bgFilename, 'public');
                return view('model', ['model' => $modelFilename, 'bg' => $bgFilename]);
            }
            return view('model', ['model' => $modelFilename, 'bg' => 'none']);
        } return "not uploaded :(";
    }

    public function uploadThree(Request $request)
    {
        if($request->hasFile('model')) {
            $modelFilename = $request->file('model')->getClientOriginalName();
            $request->file('model')->storeAs('models', $modelFilename, 'public');
            if($request->hasFile('bkgd')) {
                $bgFilename = $request->file('bkgd')->getClientOriginalName();
                $request->file('bkgd')->storeAs('backgrounds', $bgFilename, 'public');
                return view('three', ['model' => $modelFilename, 'bg' => $bgFilename]);
            }
            return view('three', ['model' => $modelFilename, 'bg' => 'none']);
        } return "not uploaded :(";
    }

    public function uploadBabylon(Request $request)
    {
        if($request->hasFile('model')) {
            $modelFilename = $request->file('model')->getClientOriginalName();
            $request->file('model')->storeAs('models', $modelFilename, 'public');
            if($request->hasFile('bkgd')) {
                $bgFilename = $request->file('bkgd')->getClientOriginalName();
                $request->file('bkgd')->storeAs('backgrounds', $bgFilename, 'public');
                return view('babylon', ['model' => $modelFilename, 'bg' => $bgFilename]);
            }
            return view('babylon', ['model' => $modelFilename, 'bg' => 'none']);
        } return "not uploaded :(";
    }

    public function uploadLeePerrySmith()
    {
        return view('leePerrySmith');
    }
}