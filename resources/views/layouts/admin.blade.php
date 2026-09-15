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
        @yield(
            'title',
            'Administración | Acacia Ediciones'
        )
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body class="admin-body">


    {{-- MENSAJES PARA SWEETALERT --}}

    <div
        id="flash-messages"
        data-success="{{ session('success') }}"
        data-error="{{ session('error') }}"
        hidden
    ></div>


    <div class="admin-layout">


        {{-- SIDEBAR --}}

        <x-admin.sidebar />


        <div class="admin-main">


            {{-- TOPBAR --}}

            <header class="admin-topbar">


                <button
                    type="button"
                    class="admin-menu-button"
                    id="adminMenuButton"
                    aria-label="Abrir menú"
                    aria-controls="adminSidebar"
                    aria-expanded="false"
                >
                    ☰
                </button>


                <div class="admin-topbar-title">

                    <span>
                        Panel de administración
                    </span>

                </div>


                <div class="admin-user-area">


                    <span class="admin-user-name">

                        {{ auth('admin')->user()?->nombre }}

                    </span>


                    <form
                        action="{{ route('admin.logout') }}"
                        method="POST"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="admin-logout-button"
                        >
                            Salir
                        </button>

                    </form>


                </div>


            </header>


            {{-- CONTENIDO --}}

            <main class="admin-content">

                @yield('content')

            </main>


        </div>


    </div>


    {{-- OVERLAY MOBILE --}}

    <div
        class="admin-overlay"
        id="adminOverlay"
    ></div>


    @stack('scripts')


</body>

</html>