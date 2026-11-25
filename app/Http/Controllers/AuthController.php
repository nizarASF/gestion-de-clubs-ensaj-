<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = new User();
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->field_of_study = $data['field_of_study'];
        // Le rôle et le statut sont imposés par le serveur, jamais par le formulaire.
        $user->role = \App\Enums\Role::Student;
        $user->status = \App\Enums\Status::Pending;
        $user->save();

        return redirect()->route('login')->with(
            'status',
            'Inscription enregistrée. Votre compte doit être validé par le BDE avant de pouvoir vous connecter.'
        );
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        // 1) Identifiants, 2) statut du compte, 3) connexion.
        $user = User::where('email', $credentials['email'])->first();
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'Identifiants incorrects.'])->onlyInput('email');
        }

        if ($user->status === \App\Enums\Status::Pending) {
            return back()->withErrors(['email' => 'Votre compte est en attente de validation par le BDE.'])->onlyInput('email');
        }

        if ($user->status === \App\Enums\Status::Rejected) {
            $message = 'Votre inscription a été refusée.';
            if ($user->rejection_reason) {
                $message .= ' Motif : ' . $user->rejection_reason;
            }

            return back()->withErrors(['email' => $message])->onlyInput('email');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('home'))->with('status', 'Connexion réussie.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Vous êtes déconnecté.');
    }
}
