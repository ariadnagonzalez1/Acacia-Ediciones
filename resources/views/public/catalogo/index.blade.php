@extends('layouts.public')

@section('title', 'Catálogo | Acacia Ediciones')

@section('content')

<section class="public-section">

    <div class="public-container">

        <div class="public-section-header public-catalog-header">

    <div>

    <a
        href="{{ route('inicio') }}"
        class="public-back-link"
    >
        ← Volver al inicio
    </a>


    <div class="catalog-heading-block">

        <span class="public-eyebrow">
            Catálogo
        </span>

        <h1>
            {{ $libros->total() }}
            títulos disponibles
        </h1>

    </div>

</div>


    <form
    action="{{ route('catalogo') }}"
    method="GET"
    class="public-search"
    id="catalogSearchForm"
>
    @if(request('categoria'))

        <input
            type="hidden"
            name="categoria"
            value="{{ request('categoria') }}"
        >

    @endif

    <div class="public-search-field">

        <input
            type="search"
            name="buscar"
            id="catalogSearchInput"
            value="{{ request('buscar') }}"
            placeholder="Buscar por título o autor"
            autocomplete="off"
        >

        <span class="public-search-icon">
            ⌕
        </span>

    </div>

</form>
</div>


        <div class="public-category-filters">

            <a
                href="{{ route('catalogo') }}"
                class="{{ !request('categoria') ? 'active' : '' }}"
            >
                Todas
            </a>

            @foreach($categorias as $categoria)

                <a
                    href="{{ route(
                        'catalogo',
                        ['categoria' => $categoria->id]
                    ) }}"
                    class="{{ request('categoria') == $categoria->id ? 'active' : '' }}"
                >
                    {{ $categoria->nombre }}
                </a>

            @endforeach

        </div>


        @if($libros->isEmpty())

            <div class="empty-state">

                <h3>
                    No encontramos libros
                </h3>

                <p>
                    Probá con otra búsqueda
                    o categoría.
                </p>

            </div>

        @else

            <div class="public-books-grid">

                @foreach($libros as $libro)

                    <x-public.libro-card
                        :libro="$libro"
                    />

                @endforeach

            </div>


            <div class="admin-pagination">
                {{ $libros->links() }}
            </div>

        @endif

    </div>

</section>

@endsection
