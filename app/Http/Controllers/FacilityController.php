<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use Illuminate\Support\Facades\File;


class FacilityController extends Controller
{
    public function index()
    {
        $facility = Facility::all();
        $facility = Facility::paginate(8);
        return view('facility.index', compact('facility'));
    }

    public function create()
    {
        return view('facility.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'description' => 'required',
        ], [
            'title.required' => 'Name is required.',
            'title.max' => 'Title should not exceed 255 characters.',
            'image.required' => 'Image is required.',
            'image.mimes' => 'Only JPEG, JPG, and PNG images are allowed.', // Custom error message for image format
            'image.max' => 'The image size should not exceed 2048 KB.', // Custom error message for image size
            'description.required' => 'Description is required'
        ]);
        $input = $request->all();
        if ($image = $request->file('image')) {
            $destinationPath = 'images/facility/';
            $profileImage = "facility" . "-" . date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = $destinationPath . $profileImage;
        }

        Facility::create($input);

        return redirect()->route('facility.index')->with(['success' => 'Data successfully saved!']);
    }

    public function show(string $id)
    {
        $facility = Facility::findOrFail($id);

        return view('facility.show', compact('facility'));
    }

    public function edit(Facility $facility)
    {
        return view('facility.update', compact('facility'));
    }

    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'title' => 'required|max:255',
            'image' => ($request->hasFile('image') || !$facility->image) ? 'image|mimes:jpeg,jpg,png|max:2048' : '', // Check if image is required
            'description' => 'required',
        ], [
            'title.required' => 'Name is required.',
            'title.max' => 'Title should not exceed 255 characters.',
            'image.required' => 'Image is required.', // Custom error message for image
            'image.mimes' => 'Only JPEG, JPG, and PNG images are allowed.', // Custom error message for image format
            'image.max' => 'The image size should not exceed 2048 KB.', // Custom error message for image size
            'description.required' => 'Description is required'
        ]);

        $input = $request->except(['_token', '_method']);

        if (!empty($facility->image) && $request->hasFile('image')) {
            $imagePath = $facility->image;

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        if ($image = $request->file('image')) {
            $destinationPath = 'images/facility/';
            $profileImage = "facility" . "-" . date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = $destinationPath . $profileImage;
        } elseif (!$request->hasFile('image') && !$facility->image) {
            unset($input['image']);
        }

        $facility->update($input);

        return redirect()->route('facility.index')
            ->with('success', 'Facility updated successfully');
    }

    public function destroy(Facility $facility)
    {
        if (!empty($facility->image)) {
            $imagePath = $facility->image;

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $facility->delete();

        return redirect()->route('facility.index')
            ->with('success', 'Facility deleted successfully');
    }
}
