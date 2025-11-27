<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController
{
    // Update password method
    public function updatePassword(Request $request)
    {
        $request->validate([
            "current_password" => "required",
            "new_password" => "required|string|min:8|confirmed",
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(["error" => "Current password is incorrect."], 403);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(["message" => "Password updated successfully."]);
    }

    // Menampilkan profil user
    public function show(Request $request)
    {
        $user = $request->user();
        return response()->json([
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email,
            "nik" => $user->nik,
            "tanggal_lahir" => $user->tanggal_lahir,
            "jenis_kelamin" => $user->jenis_kelamin,
            "nomor_telepon" => $user->nomor_telepon,
            "photo" => $user->photo ? asset("storage/" . $user->photo) : null,
            "roles" => $user->getRoleNames()->toArray(),
            "role" => $user->getRoleNames()->first() ?? "passenger",
            "created_at" => $user->created_at,
            "updated_at" => $user->updated_at,
        ]);
    }

    // Update profil user (nama, email, avatar S3)
    public function update(Request $request)
    {
        $user = $request->user();
        $data = $request->only(["name", "email", "nik", "tanggal_lahir", "jenis_kelamin", "nomor_telepon"]);
        $rules = [
            "name" => "required|string|max:255",
            "email" => "required|email|max:255|unique:users,email," . $user->id,
            "nik" => "nullable|string|max:16",
            "tanggal_lahir" => "nullable|date",
            "jenis_kelamin" => "nullable|in:L,P",
            "nomor_telepon" => "nullable|string|max:20",
        ];
        $validator = \Validator::make($data, $rules);
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }
        $user->update($data);

        return response()->json([
            "message" => "Profile updated successfully",
            "user" => [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "nik" => $user->nik,
                "tanggal_lahir" => $user->tanggal_lahir,
                "jenis_kelamin" => $user->jenis_kelamin,
                "nomor_telepon" => $user->nomor_telepon,
                "photo" => $user->photo ? asset("storage/" . $user->photo) : null,
                "roles" => $user->getRoleNames()->toArray(),
                "role" => $user->getRoleNames()->first() ?? "passenger",
            ],
        ]);
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view("profile.edit", [
            "user" => $request->user(),
        ]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag("userDeletion", [
            "password" => ["required", "current_password"],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to("/");
    }
}
