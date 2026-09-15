@extends('layouts.auth')

@section('title', 'Ingresar | Acacia Ediciones')

@section('content')

    <div class="auth-content">

        <div class="auth-title">

            <h2>
                Ingresar al panel
            </h2>

            <p>
                Ingresá tus datos para acceder a la administración.
            </p>

        </div>


        <form
            action="{{ route('admin.login.store') }}"
            method="POST"
            class="auth-form"
        >

            @csrf


            <div class="form-group">

                <label for="email">
                    Correo electrónico
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="admin@acacia.com"
                    autocomplete="email"
                    required
                    autofocus
                >

                @error('email')
                    <span class="form-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>


            <div class="form-group">

                <label for="password">
                    Contraseña
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Ingresá tu contraseña"
                    autocomplete="current-password"
                    required
                >

                @error('password')
                    <span class="form-error">
                        {{ $message }}
                    </span>
                @enderror

            </div>


            <div class="auth-options">

                <label class="checkbox-label">

                    <input
                        type="checkbox"
                        name="remember"
                        value="1"
                    >

                    <span>
                        Recordarme
                    </span>

                </label>


                <a
                    href="{{ route('admin.password.request') }}"
                    class="auth-link"
                >
                    ¿Olvidaste tu contraseña?
                </a>

            </div>


            <button
                type="submit"
                class="auth-button"
            >
                Ingresar
            </button>

        </form>


        <div class="auth-footer">

            <a href="{{ route('inicio') }}">
                ← Volver a Acacia Ediciones
            </a>

        </div>

    </div>

@endsection