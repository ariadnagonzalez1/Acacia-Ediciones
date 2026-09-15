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
        @yield('title', 'Acacia Ediciones')
    </title>

    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body class="public-body">

    <x-public.header />

    <main class="public-main">

        @yield('content')

    </main>

    <x-public.footer />

</body>

</html>