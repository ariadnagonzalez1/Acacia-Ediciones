<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.admin.login');
    }

    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],
        ]);

        $recordar = $request->boolean(
            'remember'
        );

        if (
            Auth::guard('admin')->attempt(
                $credenciales,
                $recordar
            )
        ) {
            $request->session()->regenerate();

            return redirect()
                ->intended(
                    route('admin.dashboard')
                );
        }

        return back()
            ->withErrors([
                'email' =>
                    'El correo o la contraseña son incorrectos.'
            ])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request
            ->session()
            ->invalidate();

        $request
            ->session()
            ->regenerateToken();

        return redirect()
            ->route('admin.login');
    }
}