<?php

namespace App\Http\Controllers;

use App\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function index()
    {
        $data['types'] = Type::paginate(10);
        return view('type.index', $data);
    }

    public function edit(Request $request, $id)
    {
        $data['type'] = Type::findOrFail($id);
        return view('type.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $type = Type::findOrFail($id);
        $type->status = $request->status;

        $path = 'images/type-' . $id;

        if ($request->hasFile('image')) {

            $old_image = storage_path('app/public/' . $type->image);

            if (is_file($old_image)) {
                unlink($old_image);
            }

            $image = $request->file('image');

            if ($image->isValid()) {
                $image_path = $image->store($path, 'public');
            }

        } elseif($request->has('deleteimage')) {

            $old_image = storage_path('app/public/' . $type->image);

            if (is_file($old_image)) {
                unlink($old_image);
            }

            $image_path = null;
        }

        $type->image = $image_path;

        $type->save();

        return redirect()->route('type');
    }
}
