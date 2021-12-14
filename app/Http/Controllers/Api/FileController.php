<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFile;

class FileController extends Controller
{
    public function store(StoreFile $request)
    {
        if ($request->hasFile('file')) {
            $url = asset(str_replace('public', 'storage', $request->file->store('public/uploaded-files')));
        }

        return response()->json($url);
    }
}
