<?php

namespace App\Http\Controllers;

use App\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $data['areas'] = Area::paginate(10);
        return view('area.index', $data);
    }

    public function edit(Request $request, $id)
    {
        $data['area'] = Area::findOrFail($id);
        return view('area.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $area = Area::findOrFail($id);
        $area->status = $request->status;

        $path = 'images/area-' . $id;

        if ($request->hasFile('image')) {

            $old_image = storage_path('app/public/' . $area->image);

            if (is_file($old_image)) {
                unlink($old_image);
            }

            $image = $request->file('image');

            if ($image->isValid()) {
                $image_path = $image->store($path, 'public');
            }

        } elseif($request->has('deleteimage')) {

            $old_image = storage_path('app/public/' . $area->image);

            if (is_file($old_image)) {
                unlink($old_image);
            }

            $image_path = null;
        }

        $area->image = $image_path;

        $area->save();

        return redirect()->route('area');
    }
}
