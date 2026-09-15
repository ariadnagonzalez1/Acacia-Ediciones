<footer class="public-footer">

    <div class="public-container public-footer-inner">

        <div class="public-footer-brand">

            <img
                src="{{ asset('images/logo.png') }}"
                alt="Logo Acacia Ediciones"
            >

            <p>
                Catálogo digital. Todos los títulos
                se entregan en PDF por correo electrónico.
            </p>

        </div>


        <nav class="public-footer-nav">

            <a href="{{ route('inicio') }}">
                Inicio
            </a>

            <a href="{{ route('catalogo') }}">
                Catálogo
            </a>

            <a href="{{ route('admin.login') }}">
                Administración
            </a>

        </nav>

    </div>

</footer>