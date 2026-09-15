@extends('layouts.auth')

@section('title', 'Nueva contraseña | Acacia')

@section('content')

    <div class="auth-content">

        <div class="auth-title">

            <h2>
                Nueva contraseña
            </h2>

            <p>
                Creá una nueva contraseña para recuperar
                el acceso al panel de administración.
            </p>

        </div>


        <form
            action="{{ route('admin.password.update') }}"
            method="POST"
            class="auth-form"
        >

            @csrf


            <input
                type="hidden"
                name="token"
                value="{{ $token }}"
            >


            <div class="form-group">

                <label for="email">
                    Correo electrónico
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $email) }}"
                    autocomplete="email"
                    required
                >

                @error('email')
                    <span class="form-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>


            <div class="form-group">

                <label for="password">
                    Nueva contraseña
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Mínimo 8 caracteres"
                    autocomplete="new-password"
                    required
                >

                @error('password')
                    <span class="form-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>


            <div class="form-group">

                <label for="password_confirmation">
                    Repetir contraseña
                </label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Repetí la contraseña"
                    autocomplete="new-password"
                    required
                >

            </div>


            <button
                type="submit"
                class="auth-button"
            >
                Guardar nueva contraseña
            </button>

        </form>

    </div>

@endsection