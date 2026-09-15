<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'Administración | Acacia Ediciones')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="auth-body">

    <main class="auth-wrapper">

        <section class="auth-card">

            <header class="auth-header">

                <div class="auth-logo">

                    <div class="auth-logo-image">

    <img
        src="{{ asset('images/logo.png') }}"
        alt="Logo Acacia Ediciones"
    >

</div>

                    <div class="auth-brand-text">
                        <h1>Acacia Ediciones</h1>
                        <p>Administración</p>
                    </div>

                </div>

            </header>


            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif


            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif


            @yield('content')

        </section>

    </main>

</body>

</html>