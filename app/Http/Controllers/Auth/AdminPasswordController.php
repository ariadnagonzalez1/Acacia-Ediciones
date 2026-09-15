<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AdminPasswordController extends Controller
{
    public function showForgotPassword()
    {
        return view(
            'auth.admin.forgot-password'
        );
    }

    public function sendResetLink(
        Request $request
    ) {
        $request->validate([
            'email' => [
                'required',
                'email',
            ],
        ]);

        $status = Password::broker('administradores')
            ->sendResetLink(
                $request->only('email')
            );

        return $status === Password::RESET_LINK_SENT

            ? back()->with(
                'success',
                __($status)
            )

            : back()->withErrors([
                'email' => __($status)
            ]);
    }

    public function showResetPassword(
        Request $request,
        string $token
    ) {
        return view(
            'auth.admin.reset-password',
            [
                'token' => $token,
                'email' => $request->email,
            ]
        );
    }

    public function resetPassword(
        Request $request
    ) {
        $request->validate([
            'token' => [
                'required',
            ],

            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'confirmed',
                'min:8',
            ],
        ]);

        $status = Password::broker('administradores')
            ->reset(
                $request->only(
                    'email',
                    'password',
                    'password_confirmation',
                    'token'
                ),

                function ($administrador) use ($request) {

                    $administrador->forceFill([
                        'password' => Hash::make(
                            $request->password
                        ),

                        'remember_token' =>
                            Str::random(60),

                    ])->save();
                }
            );

        return $status === Password::PASSWORD_RESET

            ? redirect()
                ->route('admin.login')
                ->with(
                    'success',
                    'Contraseña restablecida correctamente.'
                )

            : back()->withErrors([
                'email' => __($status)
            ]);
    }
}