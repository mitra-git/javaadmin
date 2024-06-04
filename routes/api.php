<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/upload-image-360', function (Request $request) {
    $request->validate([
        'image_360' => 'required|image|mimes:jpg',
    ]);

    if ($image = $request->file('image_360')) {
        $destinationPath = 'images/projectType/image_360/';
        $profileImage = "image" . "-" . date('YmdHis') . "." . $image->getClientOriginalExtension();
        $image->move(public_path($destinationPath), $profileImage);

        return response()->json(['path' => $destinationPath . $profileImage], 200);
    }

    return response()->json(['error' => 'Image upload failed!'], 400);
});
