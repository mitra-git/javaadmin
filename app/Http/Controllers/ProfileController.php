<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;


class ProfileController extends Controller
{
    public function index()
    {
        $user = User::all();
        $user = User::paginate(8);
        return view('user.index', compact('user'));
    }

    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return view('user.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('user.update', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        try {
            $rules = [
                'name' => 'required',
                'address' => 'required',
                'phone_number' => 'required',
                'email' => 'required',
            ];

            if ($request->hasFile('photo_profile')) {
                $rules['photo_profile'] = 'image|mimes:jpeg,jpg,png|max:2048';
            }

            $request->validate($rules);

            $input = $request->except(['_token', '_method']);

            if (!empty($user->photo_profile) && $request->hasFile('photo_profile')) {
                $imagePath = $user->photo_profile;

                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

            if ($photo_profile = $request->file('photo_profile')) {
                $destinationPath = 'images/user/photo_profile/';
                $profileImage = "user" . "-" . "photo_profile" . date('YmdHis') . "." . $photo_profile->getClientOriginalExtension();
                $photo_profile->move($destinationPath, $profileImage);
                $input['photo_profile'] = $destinationPath . $profileImage;
            } elseif (!$request->hasFile('photo_profile') && !$user->photo_profile) {
                unset($input['photo_profile']);
            }

            $user->update($input);

            return redirect()->route('user.index')
                ->with('success', 'User updated successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update user. Please try again.');
        }
    }

    public function changePassword(Request $request, User $user)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect.'])->withInput();
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('user.index')->with('success', 'Password changed successfully');
    }
}
