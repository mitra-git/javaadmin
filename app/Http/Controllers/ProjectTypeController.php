<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectType;
use App\Models\Project;
use Illuminate\Support\Facades\File;

class ProjectTypeController extends Controller
{
    public function index()
    {
        $projectType = ProjectType::all();
        $projectType = ProjectType::paginate(8);
        $jenisProject = Project::all();

        return view('projectType.index', compact('projectType', 'jenisProject'));
    }

    public function create()
    {
        $projectId = Project::all();
        return view('projectType.create', compact('projectId'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_project' => 'required',
            'name' => 'required|max:255',
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'image_360' => 'required|image|mimes:jpg',
            'small_description' => 'required',
            'luas_bangunan' => 'required|max:255',
            'luas_tanah' => 'required|max:255',
            'fondasi' => 'required|max:255',
            'dinding' => 'required|max:255',
            'plafon' => 'required|max:255',
            'kamar_tidur' => 'required|max:255',
            'kamar_mandi' => 'required|max:255',
            'carport' => 'required|max:255',
            'air' => 'required|max:255',
        ], [
            'id_project.required' => 'Project id is required.',
            'name.required' => 'Name is required.',
            'name.max' => 'Name should not exceed 255 characters.',
            'image.required' => 'Image is required.',
            'image.mimes' => 'Only JPEG, JPG, and PNG images are allowed.', // Custom error message for image format
            'image.max' => 'The image size should not exceed 2048 KB.', // Custom error message for image size
            'image_360.required' => 'Image is required.',
            'image_360.mimes' => 'Only JPG images are allowed.', // Custom error message for image format
            'small_description.required' => 'Description is required',
            'luas_bangunan.required' => 'Luas Bangunan is required.',
            'luas_bangunan.max' => 'Luas Bangunan should not exceed 255 characters.',
            'luas_tanah.required' => 'Luas Tanah is required.',
            'luas_tanah.max' => 'Luas Tanah should not exceed 255 characters.',
            'fondasi.required' => 'Fondasi is required.',
            'fondasi.max' => 'Fondasi should not exceed 255 characters.',
            'dinding.required' => 'Dinding is required.',
            'dinding.max' => 'Dinding should not exceed 255 characters.',
            'plafon.required' => 'Plafon is required.',
            'plafon.max' => 'Plafon should not exceed 255 characters.',
            'kamar_tidur.required' => 'Kamar Tidur is required.',
            'kamar_tidur.max' => 'Kamar Tidur should not exceed 255 characters.',
            'kamar_mandi.required' => 'Kamar Mandi is required.',
            'kamar_mandi.max' => 'Kamar Mandi should not exceed 255 characters.',
            'carport.required' => 'Carport is required.',
            'carport.max' => 'Carport should not exceed 255 characters.',
            'air.required' => 'Air is required.',
            'air.max' => 'Air should not exceed 255 characters.',
        ]);
        $input = $request->all();
        if ($image = $request->file('image')) {
            $destinationPath = 'images/projectType/image/';
            $profileImage = "image" . "-" . date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = $destinationPath . $profileImage;
        }

        if ($image_360 = $request->file('image_360')) {
            $destinationPath = 'images/projectType/image_360/';
            $profileImage = "image" . "-" . date('YmdHis') . "." . $image_360->getClientOriginalExtension();
            $image_360->move($destinationPath, $profileImage);
            $input['image_360'] = $destinationPath . $profileImage;
        }

        ProjectType::create($input);

        return redirect()->route('projectType.index')->with(['success' => 'Data successfully saved!']);
    }

    public function show(string $id)
    {
        $projectType = ProjectType::findOrFail($id);

        return view('projectType.show', compact('projectType'));
    }

    public function edit(ProjectType $projectType)
    {
        $projectImageAll = Project::all();

        return view('projectType.update', compact('projectType', 'projectImageAll'));
    }

    public function update(Request $request, ProjectType $projectType)
    {
        $request->validate([
            'id_project' => 'required',
            'name' => 'required|max:255',
            'image' => ($request->hasFile('image') || !$projectType->image) ? 'image|mimes:jpeg,jpg,png|max:2048' : '', // Check if image is required
            'image_360' => ($request->hasFile('image_360') || !$projectType->image_360) ? 'image|mimes:jpg' : '', // Check if image is required
            'small_description' => 'required',
            'luas_bangunan' => 'required|max:255',
            'luas_tanah' => 'required|max:255',
            'fondasi' => 'required|max:255',
            'dinding' => 'required|max:255',
            'plafon' => 'required|max:255',
            'kamar_tidur' => 'required|max:255',
            'kamar_mandi' => 'required|max:255',
            'carport' => 'required|max:255',
            'air' => 'required|max:255',
        ], [
            'id_project.required' => 'Project id is required.',
            'name.required' => 'Name is required.',
            'name.max' => 'Name should not exceed 255 characters.',
            'image.required' => 'Image is required.',
            'image.mimes' => 'Only JPEG, JPG, and PNG images are allowed.', // Custom error message for image format
            'image.max' => 'The image size should not exceed 2048 KB.', // Custom error message for image size
            'image_360.required' => 'Image is required.',
            'image_360.mimes' => 'Only JPG images are allowed.', // Custom error message for image format
            'small_description.required' => 'Description is required',
            'luas_bangunan.required' => 'Luas Bangunan is required.',
            'luas_bangunan.max' => 'Luas Bangunan should not exceed 255 characters.',
            'luas_tanah.required' => 'Luas Tanah is required.',
            'luas_tanah.max' => 'Luas Tanah should not exceed 255 characters.',
            'fondasi.required' => 'Fondasi is required.',
            'fondasi.max' => 'Fondasi should not exceed 255 characters.',
            'dinding.required' => 'Dinding is required.',
            'dinding.max' => 'Dinding should not exceed 255 characters.',
            'plafon.required' => 'Plafon is required.',
            'plafon.max' => 'Plafon should not exceed 255 characters.',
            'kamar_tidur.required' => 'Kamar Tidur is required.',
            'kamar_tidur.max' => 'Kamar Tidur should not exceed 255 characters.',
            'kamar_mandi.required' => 'Kamar Mandi is required.',
            'kamar_mandi.max' => 'Kamar Mandi should not exceed 255 characters.',
            'carport.required' => 'Carport is required.',
            'carport.max' => 'Carport should not exceed 255 characters.',
            'air.required' => 'Air is required.',
            'air.max' => 'Air should not exceed 255 characters.',
        ]);

        $input = $request->except(['_token', '_method']);

        if (!empty($projectType->image) && $request->hasFile('image')) {
            $imagePath = $projectType->image;

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        if (!empty($projectType->image_360) && $request->hasFile('image_360')) {
            $imagePath2 = $projectType->image_360;

            if (File::exists($imagePath2)) {
                File::delete($imagePath2);
            }
        }

        if ($image = $request->file('image')) {
            $destinationPath = 'images/projectType/image/';
            $profileImage = "projectType" . "-" . date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = $destinationPath . $profileImage;
        } elseif (!$request->hasFile('image') && !$projectType->image) {
            unset($input['image']);
        }

        if ($image_360 = $request->file('image_360')) {
            $destinationPath = 'images/projectType/image_360/';
            $profileImage = "projectType" . "-" . date('YmdHis') . "." . $image_360->getClientOriginalExtension();
            $image_360->move($destinationPath, $profileImage);
            $input['image_360'] = $destinationPath . $profileImage;
        } elseif (!$request->hasFile('image_360') && !$projectType->image_360) {
            unset($input['image_360']);
        }

        $projectType->update($input);

        return redirect()->route('projectType.index')
            ->with('success', 'ProjectType updated successfully');
    }

    public function destroy(ProjectType $projectType)
    {
        if (!empty($projectType->image)) {
            $imagePath = $projectType->image;

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if (!empty($projectType->image_360)) {
            $imagePath2 = $projectType->image_360;

            if (file_exists($imagePath2)) {
                unlink($imagePath2);
            }
        }

        $projectType->delete();

        return redirect()->route('projectType.index')
            ->with('success', 'ProjectType deleted successfully');
    }
}
