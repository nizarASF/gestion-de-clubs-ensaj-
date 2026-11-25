<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function update(ProfileRequest $request)
    {
        $user = auth()->user();
        $user->fill($request->validated());
        $user->save();

        return back()->with('status', 'Votre profil a été mis à jour.');
    }

    public function password(PasswordRequest $request)
    {
        $user = auth()->user();
        $user->password = Hash::make($request->validated('password'));
        $user->save();

        return back()->with('status', 'Votre mot de passe a été modifié.');
    }
}
