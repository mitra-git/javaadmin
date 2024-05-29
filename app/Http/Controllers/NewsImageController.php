<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsImage;
use App\Models\News;
use Illuminate\Support\Facades\File;

class NewsImageController extends Controller
{
    public function index()
    {
        $newsImage = NewsImage::all();
        $newsImage = NewsImage::paginate(8);
        $jenisImage = News::all();
        return view('newsImage.index', compact('newsImage', 'jenisImage'));
    }

    public function create()
    {
        $newsId = News::all();
        return view('newsImage.create', compact('newsId'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id_news' => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'id_news.required' => 'News id is required.',
            'image.required' => 'Image is required.',
            'image.mimes' => 'Only JPEG, JPG, and PNG images are allowed.',
            'image.max' => 'The image size should not exceed 2048 KB.',
        ]);

        $input = $request->all();
        if ($image = $request->file('image')) {
            $destinationPath = 'images/newsImage/';
            $profileImage = "newsImage" . "-" . date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = $destinationPath . $profileImage;
        }

        NewsImage::create($input);

        return redirect()->route('newsImage.index')->with(['success' => 'Data successfully saved!']);
    }

    public function show(string $id)
    {
        $newsImage = NewsImage::findOrFail($id);

        return view('newsImage.show', compact('newsImage'));
    }

    public function edit(NewsImage $newsImage)
    {
        $newsImageAll = News::all();

        return view('newsImage.update', compact('newsImage', 'newsImageAll'));
    }

    public function update(Request $request, NewsImage $newsImage)
    {
        $request->validate([
            'id_news' => 'required',
            'image' => ($request->hasFile('image') || !$newsImage->image) ? 'image|mimes:jpeg,jpg,png|max:2048' : '', // Check if image is required
        ], [
            'id_news.required' => 'News id is required.',
            'image.required' => 'Image is required.',
            'image.mimes' => 'Only JPEG, JPG, and PNG images are allowed.',
            'image.max' => 'The image size should not exceed 2048 KB.',
        ]);

        $input = $request->except(['_token', '_method']);

        if (!empty($newsImage->image) && $request->hasFile('image')) {
            $imagePath = $newsImage->image;

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        if ($image = $request->file('image')) {
            $destinationPath = 'images/newsImage/';
            $profileImage = "newsImage" . "-" . date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = $destinationPath . $profileImage;
        } elseif (!$request->hasFile('image') && !$newsImage->image) {
            unset($input['image']);
        }

        $newsImage->update($input);

        return redirect()->route('newsImage.index')
            ->with('success', 'NewsImage updated successfully');
    }

    public function destroy(NewsImage $newsImage)
    {
        if (!empty($newsImage->image)) {
            $imagePath = $newsImage->image;

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $newsImage->delete();

        return redirect()->route('newsImage.index')
            ->with('success', 'NewsImage deleted successfully');
    }
}
