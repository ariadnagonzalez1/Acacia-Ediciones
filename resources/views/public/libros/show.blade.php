@extends('layouts.public')

@section('title', $libro->titulo . ' | Acacia Ediciones')

@section('content')

<section class="public-section">

    <div class="public-container">

        <a
            href="{{ route('catalogo') }}"
            class="public-back-link"
        >
            ← Volver al catálogo
        </a>


        <div class="book-detail">

            <div class="book-detail-cover">

                @if($libro->portada)

                    <img
                        src="{{ asset('storage/' . $libro->portada) }}"
                        alt="{{ $libro->titulo }}"
                    >

                @else

                    <div class="public-book-placeholder">
                        Sin portada
                    </div>

                @endif

            </div>


            <div class="book-detail-info">

                <span class="public-eyebrow">
                    {{ $libro->categoria?->nombre }}
                </span>

                <h1>
                    {{ $libro->titulo }}
                </h1>

                <p class="book-detail-author">
                    {{ $libro->autor }}
                </p>


                <div class="book-detail-meta">

                    @if($libro->paginas)

                        <span>
                            {{ $libro->paginas }} páginas
                        </span>

                    @endif

                    @if($libro->anio_edicion)

                        <span>
                            Edición {{ $libro->anio_edicion }}
                        </span>

                    @endif

                    <span>
                        PDF
                    </span>

                </div>


                @if($libro->informacion)

                    <div class="book-detail-description">

                        <h2>
                            Sobre este libro
                        </h2>

                        <p>
                            {{ $libro->informacion }}
                        </p>

                    </div>

                @endif


                <div class="book-detail-purchase">

                    <strong class="book-detail-price">

                        $ {{ number_format(
                            $libro->precio,
                            0,
                            ',',
                            '.'
                        ) }}

                    </strong>


                    <form
                        action="{{ route(
                            'carrito.libro.agregar',
                            $libro
                        ) }}"
                        method="POST"
                        class="add-to-cart-form"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="public-primary-button"
                        >
                            Agregar al carrito
                        </button>

                    </form>

                </div>

            </div>

        </div>
        @if($recomendados->isNotEmpty())

    <section class="book-recommendations">

        <div class="public-section-header">

            <div>

                <span class="public-eyebrow">
                    Recomendaciones
                </span>

                <h2>
                    También te puede interesar
                </h2>

                <p>
                    Títulos relacionados con este ebook.
                </p>

            </div>

        </div>


        <div class="public-books-grid">

            @foreach($recomendados as $recomendado)

                <x-public.libro-card
                    :libro="$recomendado"
                />

            @endforeach

        </div>

    </section>

@endif

    </div>

</section>

@endsection