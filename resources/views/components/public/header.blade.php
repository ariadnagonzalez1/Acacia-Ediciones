<header class="public-header">

    <div class="public-container public-header-inner">

        <a
            href="{{ route('inicio') }}"
            class="public-brand"
        >

            <div class="public-brand-logo">

                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="Logo Acacia Ediciones"
                >

            </div>

            <div>

                <strong>
                    Acacia Ediciones
                </strong>

                <span>
                    Libros en PDF
                </span>

            </div>

        </a>


        <button
            type="button"
            class="public-menu-button"
            id="publicMenuButton"
            aria-label="Abrir menú"
        >
            ☰
        </button>


        <nav
            class="public-nav"
            id="publicNav"
        >

            {{-- CATÁLOGO --}}

            <a
                href="{{ route('catalogo') }}"
                class="{{ request()->routeIs('catalogo') ? 'active' : '' }}"
            >
                Catálogo
            </a>


            {{-- ADMINISTRACIÓN --}}

            <a
                href="{{ auth('admin')->check()
                    ? route('admin.dashboard')
                    : route('admin.login') }}"
            >
                Administración
            </a>


            {{-- CARRITO --}}

            @php

                $cantidadCarrito = collect(
                    session('carrito', [])
                )->sum(function ($item) {

                    return $item['cantidad'] ?? 1;

                });

            @endphp


            <a
                href="{{ route('carrito.index') }}"
                class="public-cart-link"
            >

                Carrito

                <span
                    id="publicCartCount"
                    class="public-cart-count {{ $cantidadCarrito === 0 ? 'is-hidden' : '' }}"
                >
                    {{ $cantidadCarrito }}
                </span>

            </a>

        </nav>

    </div>

</header>