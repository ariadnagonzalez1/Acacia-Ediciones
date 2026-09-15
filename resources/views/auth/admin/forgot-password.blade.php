@extends('layouts.auth')

@section('title', 'Recuperar contraseña | Acacia')

@section('content')

    <div class="auth-content">

        <div class="auth-title">

            <h2>
                Recuperar contraseña
            </h2>

            <p>
                Ingresá el correo del administrador y te enviaremos
                un enlace para crear una nueva contraseña.
            </p>

        </div>


        <form
            action="{{ route('admin.password.email') }}"
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


            <button
                type="submit"
                class="auth-button"
            >
                Enviar enlace
            </button>

        </form>


        <div class="auth-footer">

            <a href="{{ route('admin.login') }}">
                ← Volver al inicio de sesión
            </a>

        </div>

    </div>

@endsection