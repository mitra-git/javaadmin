<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectImage;
use App\Models\Project;
use Illuminate\Support\Facades\File;

class ProjectImageController extends Controller
{
    public function index()
    {
        $projectImage = ProjectImage::all();
        $projectImage = ProjectImage::paginate(8);
        $jenisImage = Project::all();
        return view('projectImage.index', compact('projectImage', 'jenisImage'));
    }

    public function create()
    {
        $projectId = Project::all();
        return view('projectImage.create', compact('projectId'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_project_image' => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'id_project.required' => 'Project id is required.',
            'image.required' => 'Image is required.',
            'image.mimes' => 'Only JPEG, JPG, and PNG images are allowed.',
            'image.max' => 'The image size should not exceed 2048 KB.',
        ]);

        $input = $request->all();
        if ($image = $request->file('image')) {
            $destinationPath = 'images/projectImage/image/';
            $profileImage = "image" . "-" . date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = $destinationPath . $profileImage;
        }

        ProjectImage::create($input);

        return redirect()->route('projectImage.index')->with(['success' => 'Data successfully saved!']);
    }

    public function show(string $id)
    {
        $projectImage = ProjectImage::findOrFail($id);

        return view('projectImage.show', compact('projectImage'));
    }

    public function edit(ProjectImage $projectImage)
    {
        $projectImageAll = Project::all();

        return view('projectImage.update', compact('projectImage', 'projectImageAll'));
    }

    public function update(Request $request, ProjectImage $projectImage)
    {
        $request->validate([
            'id_project_image' => 'required',
            'image' => ($request->hasFile('image') || !$projectImage->image) ? 'image|mimes:jpeg,jpg,png' : '', // Check if image is required
        ], [
            'id_project_image.required' => 'Project id is required.',
            'image.required' => 'Image is required.',
            'image.mimes' => 'Only JPEG, JPG, and PNG images are allowed.',
            'image.max' => 'The image size should not exceed 2048 KB.',
        ]);

        $input = $request->except(['_token', '_method']);

        if (!empty($projectImage->image) && $request->hasFile('image')) {
            $imagePath = $projectImage->image;

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        if ($image = $request->file('image')) {
            $destinationPath = 'images/projectImage/image/';
            $profileImage = "projectImage" . "-" . date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = $destinationPath . $profileImage;
        } elseif (!$request->hasFile('image') && !$projectImage->image) {
            unset($input['image']);
        }

        $projectImage->update($input);

        return redirect()->route('projectImage.index')
            ->with('success', 'ProjectImage updated successfully');
    }

    public function destroy(ProjectImage $projectImage)
    {
        if (!empty($projectImage->image)) {
            $imagePath = $projectImage->image;

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $projectImage->delete();

        return redirect()->route('projectImage.index')
            ->with('success', 'ProjectImage deleted successfully');
    }
}
