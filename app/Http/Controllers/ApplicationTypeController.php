<?php

namespace App\Http\Controllers;

use App\ApplicationType;
use Illuminate\Http\Request;

class ApplicationTypeController extends Controller
{
    public function index()
    {
        $data['applicationTypes'] = ApplicationType::paginate(10);

        return view('application-type.index', $data);
    }

    public function edit(Request $request, $id)
    {
        $data['applicationType'] = ApplicationType::findOrFail($id);

        return view('application-type.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $applicationType = ApplicationType::findOrFail($id);
        $applicationType->status = $request->status;

        $path = 'images/application-type-' . $id;

        if ($request->hasFile('image')) {

            $old_image = storage_path('app/public/' . $applicationType->image);

            if (is_file($old_image)) {
                unlink($old_image);
            }

            $image = $request->file('image');

            if ($image->isValid()) {
                $image_path = $image->store($path, 'public');
            }

        } elseif($request->has('deleteimage')) {

            $old_image = storage_path('app/public/' . $applicationType->image);

            if (is_file($old_image)) {
                unlink($old_image);
            }

            $image_path = null;
        }

        $applicationType->image = $image_path;

        $applicationType->save();

        return redirect()->route('application-type');
    }
}
