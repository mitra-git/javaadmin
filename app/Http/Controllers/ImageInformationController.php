<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImageInformation;
use Illuminate\Support\Facades\File;

class ImageInformationController extends Controller
{
    public function index()
    {
        $imageInformation = ImageInformation::all();
        $imageInformation = ImageInformation::paginate(8);
        return view('imageInformation.index', compact('imageInformation'));
    }

    public function create()
    {
        return view('imageInformation.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'image.required' => 'Image is required.',
            'image.mimes' => 'Only JPEG, JPG, and PNG images are allowed.', // Custom error message for image format
            'image.max' => 'The image size should not exceed 2048 KB.', // Custom error message for image size
        ]);
        $input = $request->all();
        if ($image = $request->file('image')) {
            $destinationPath = 'images/imageInformation/';
            $profileImage = "imageInformation" . "-" . date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = $destinationPath . $profileImage;
        }

        ImageInformation::create($input);

        return redirect()->route('imageInformation.index')->with(['success' => 'Data successfully saved!']);
    }

    public function show(string $id)
    {
        $imageInformation = ImageInformation::findOrFail($id);

        return view('imageInformation.show', compact('imageInformation'));
    }

    public function edit(ImageInformation $imageInformation)
    {
        return view('imageInformation.update', compact('imageInformation'));
    }

    public function update(Request $request, ImageInformation $imageInformation)
    {
        $request->validate([
            'image' => ($request->hasFile('image') || !$imageInformation->image) ? 'image|mimes:jpeg,jpg,png|max:2048' : '', // Check if image is required
        ], [
            'image.required' => 'Image is required.', // Custom error message for image
            'image.mimes' => 'Only JPEG, JPG, and PNG images are allowed.', // Custom error message for image format
            'image.max' => 'The image size should not exceed 2048 KB.', // Custom error message for image size
        ]);

        $input = $request->except(['_token', '_method']);

        if (!empty($imageInformation->image) && $request->hasFile('image')) {
            $imagePath = $imageInformation->image;

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        if ($image = $request->file('image')) {
            $destinationPath = 'images/imageInformation/';
            $profileImage = "imageInformation" . "-" . date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = $destinationPath . $profileImage;
        } elseif (!$request->hasFile('image') && !$imageInformation->image) {
            unset($input['image']);
        }

        $imageInformation->update($input);

        return redirect()->route('imageInformation.index')
            ->with('success', 'ImageInformation updated successfully');
    }

    public function destroy(ImageInformation $imageInformation)
    {
        if (!empty($imageInformation->image)) {
            $imagePath = $imageInformation->image;

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $imageInformation->delete();

        return redirect()->route('imageInformation.index')
            ->with('success', 'ImageInformation deleted successfully');
    }
}
