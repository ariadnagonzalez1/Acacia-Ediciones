<aside
    class="admin-sidebar"
    id="adminSidebar"
>

    <div class="admin-sidebar-header">

        <a
            href="{{ route('inicio') }}"
            class="admin-sidebar-brand"
            title="Volver a la tienda"
        >

            <div class="admin-brand-logo">

                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="Logo Acacia Ediciones"
                >

            </div>

            <div>
                <h2>Acacia</h2>
                <span>Administración</span>
            </div>

        </a>


        <button
            type="button"
            class="admin-sidebar-close"
            id="adminSidebarClose"
            aria-label="Cerrar menú"
        >
            ×
        </button>

    </div>


    <nav class="admin-nav">

        <a
            href="{{ route('admin.dashboard') }}"
            class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
        >
            <span class="admin-nav-icon">⌂</span>
            <span>Resumen</span>
        </a>


        <a
            href="{{ route('admin.libros.index') }}"
            class="admin-nav-link {{ request()->routeIs('admin.libros.*') ? 'active' : '' }}"
        >
            <span class="admin-nav-icon">▤</span>
            <span>Libros</span>
        </a>


        <a
            href="{{ route('admin.categorias.index') }}"
            class="admin-nav-link {{ request()->routeIs('admin.categorias.*') ? 'active' : '' }}"
        >
            <span class="admin-nav-icon">◫</span>
            <span>Categorías</span>
        </a>


        <a
            href="{{ route('admin.promociones.index') }}"
            class="admin-nav-link {{ request()->routeIs('admin.promociones.*') ? 'active' : '' }}"
        >
            <span class="admin-nav-icon">%</span>
            <span>Promociones</span>
        </a>


        <a
            href="{{ route('admin.correos.index') }}"
            class="admin-nav-link {{ request()->routeIs('admin.correos.*') ? 'active' : '' }}"
        >
            <span class="admin-nav-icon">✉</span>
            <span>Correos automáticos</span>
        </a>


        <a
            href="{{ route('admin.ventas.index') }}"
            class="admin-nav-link {{ request()->routeIs('admin.ventas.*') ? 'active' : '' }}"
        >
            <span class="admin-nav-icon">$</span>
            <span>Ventas</span>
        </a>


        <a
            href="{{ route('admin.clientes.index') }}"
            class="admin-nav-link {{ request()->routeIs('admin.clientes.*') ? 'active' : '' }}"
        >
            <span class="admin-nav-icon">♙</span>
            <span>Clientes</span>
        </a>

    </nav>


    {{-- VOLVER A LA TIENDA --}}

    <div class="admin-sidebar-footer">

        <a
            href="{{ route('inicio') }}"
            class="admin-store-link"
        >
            <span class="admin-store-arrow">
                ←
            </span>

            <span>
                Volver a la tienda
            </span>
        </a>

    </div>

</aside>