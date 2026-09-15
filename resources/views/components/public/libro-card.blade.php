@props([
    'libro'
])

<article class="public-book-card">


    {{-- PORTADA CLICKEABLE --}}

    <a
        href="{{ route('libro.show', $libro) }}"
        class="public-book-cover public-book-cover-link"
    >

        @if($libro->portada)

            <img
                src="{{ asset(
                    'storage/' . $libro->portada
                ) }}"
                alt="{{ $libro->titulo }}"
            >

        @else

            <div class="public-book-placeholder">
                Sin portada
            </div>

        @endif

    </a>


    {{-- INFORMACIÓN DEL LIBRO --}}

    <div class="public-book-content">


        {{-- CATEGORÍA --}}

        <span class="public-book-category">
            {{ $libro->categoria?->nombre }}
        </span>


        {{-- TÍTULO CLICKEABLE --}}

        <h3>

            <a
                href="{{ route('libro.show', $libro) }}"
                class="public-book-title-link"
            >
                {{ $libro->titulo }}
            </a>

        </h3>


        {{-- AUTOR --}}

        <p class="public-book-author">
            {{ $libro->autor }}
        </p>


        {{-- PRECIO --}}

        <strong class="public-book-price">

            $ {{ number_format(
                $libro->precio,
                0,
                ',',
                '.'
            ) }}

        </strong>


        {{-- AGREGAR AL CARRITO SIN RECARGAR --}}

        <form
            action="{{ route(
                'carrito.libro.agregar',
                $libro
            ) }}"
            method="POST"
            class="add-to-cart-form"
            data-title="{{ $libro->titulo }}"
        >

            @csrf

            <button
                type="submit"
                class="public-secondary-button"
            >
                Agregar al carrito
            </button>

        </form>

    </div>

</article>