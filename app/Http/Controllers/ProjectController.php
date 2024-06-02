<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\File;

class ProjectController extends Controller
{
    public function index()
    {
        $project = Project::all();
        $project = Project::paginate(8);
        return view('project.index', compact('project'));
    }

    public function create()
    {
        return view('project.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'header_image' => 'required|image|mimes:jpeg,jpg,png|max:4096',
            'image' => 'required|image|mimes:jpeg,jpg,png|max:4096',
            'description' => 'required',
            'siteplan' => 'required|image|mimes:jpeg,jpg,png|max:4096',
            'location' => 'required',
        ], [
            'title.required' => 'Name is required.',
            'title.max' => 'Title should not exceed 255 characters.',
            'image.required' => 'Image is required.',
            'image.mimes' => 'Only JPEG, JPG, and PNG images are allowed.', // Custom error message for image format
            'image.max' => 'The image size should not exceed 4096 KB.', // Custom error message for image size
            'header_image.required' => 'Image is required.',
            'header_image.mimes' => 'Only JPEG, JPG, and PNG images are allowed.', // Custom error message for image format
            'header_image.max' => 'The image size should not exceed 4096 KB.', // Custom error message for image size
            'description.required' => 'Description is required',
            'siteplan.required' => 'Image is required.',
            'siteplan.mimes' => 'Only JPEG, JPG, and PNG images are allowed.', // Custom error message for image format
            'siteplan.max' => 'The image size should not exceed 4096 KB.', // Custom error message for image size
            'location.required' => 'Location is required',
        ]);
        $input = $request->all();
        if ($image = $request->file('image')) {
            $destinationPath = 'images/project/image/';
            $profileImage = "image" . "-" . date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = $destinationPath . $profileImage;
        }
        if ($header_image = $request->file('header_image')) {
            $destinationPath = 'images/project/header_image/';
            $profileImage = "header_image" . "-" . date('YmdHis') . "." . $image->getClientOriginalExtension();
            $header_image->move($destinationPath, $profileImage);
            $input['header_image'] = $destinationPath . $profileImage;
        }
        if ($siteplan = $request->file('siteplan')) {
            $destinationPath = 'images/project/siteplan/';
            $profileImage = "siteplan" . "-" . date('YmdHis') . "." . $image->getClientOriginalExtension();
            $siteplan->move($destinationPath, $profileImage);
            $input['siteplan'] = $destinationPath . $profileImage;
        }

        Project::create($input);

        return redirect()->route('project.index')->with(['success' => 'Data successfully saved!']);
    }

    public function show(string $id)
    {
        $project = Project::findOrFail($id);

        return view('project.show', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('project.update', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|max:255',
            'image' => ($request->hasFile('image') || !$project->image) ? 'image|mimes:jpeg,jpg,png|max:4096' : '', // Check if image is required
            'header_image' => ($request->hasFile('header_image') || !$project->header_image) ? 'image|mimes:jpeg,jpg,png|max:4096' : '', // Check if image is required
            'siteplan' => ($request->hasFile('siteplan') || !$project->siteplan) ? 'image|mimes:jpeg,jpg,png|max:4096' : '', // Check if image is required
            'description' => 'required',
            'location' => 'required',
        ], [
            'title.required' => 'Name is required.',
            'title.max' => 'Title should not exceed 255 characters.',
            'image.required' => 'Image is required.',
            'image.mimes' => 'Only JPEG, JPG, and PNG images are allowed.', // Custom error message for image format
            'image.max' => 'The image size should not exceed 4096 KB.', // Custom error message for image size
            'header_image.required' => 'Image is required.',
            'header_image.mimes' => 'Only JPEG, JPG, and PNG images are allowed.', // Custom error message for image format
            'header_image.max' => 'The image size should not exceed 4096 KB.', // Custom error message for image size
            'description.required' => 'Description is required',
            'siteplan.required' => 'Image is required.',
            'siteplan.mimes' => 'Only JPEG, JPG, and PNG images are allowed.', // Custom error message for image format
            'siteplan.max' => 'The image size should not exceed 4096 KB.', // Custom error message for image size
            'location.required' => 'Location is required',
        ]);

        $input = $request->except(['_token', '_method']);

        if (!empty($project->image) && $request->hasFile('image')) {
            $imagePath = $project->image;

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        if (!empty($project->header_image) && $request->hasFile('header_image')) {
            $imagePath = $project->header_image;

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        if (!empty($project->siteplan) && $request->hasFile('siteplan')) {
            $imagePath = $project->siteplan;

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        if ($image = $request->file('image')) {
            $destinationPath = 'images/project/image/';
            $profileImage = "project" . "-" . date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = $destinationPath . $profileImage;
        } elseif (!$request->hasFile('image') && !$project->image) {
            unset($input['image']);
        }

        if ($header_image = $request->file('header_image')) {
            $destinationPath = 'images/project/header_image/';
            $profileImage = "project" . "-" . date('YmdHis') . "." . $header_image->getClientOriginalExtension();
            $header_image->move($destinationPath, $profileImage);
            $input['header_image'] = $destinationPath . $profileImage;
        } elseif (!$request->hasFile('header_image') && !$project->header_image) {
            unset($input['header_image']);
        }

        if ($siteplan = $request->file('siteplan')) {
            $destinationPath = 'images/project/siteplan/';
            $profileImage = "project" . "-" . date('YmdHis') . "." . $siteplan->getClientOriginalExtension();
            $siteplan->move($destinationPath, $profileImage);
            $input['siteplan'] = $destinationPath . $profileImage;
        } elseif (!$request->hasFile('siteplan') && !$project->siteplan) {
            unset($input['siteplan']);
        }

        $project->update($input);

        return redirect()->route('project.index')
            ->with('success', 'Project updated successfully');
    }

    public function destroy(Project $project)
    {
        if (!empty($project->image)) {
            $imagePath = $project->image;

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if (!empty($project->header_image)) {
            $imagePath = $project->header_image;

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if (!empty($project->siteplan)) {
            $imagePath = $project->siteplan;

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $project->delete();

        return redirect()->route('project.index')
            ->with('success', 'Project deleted successfully');
    }
}
