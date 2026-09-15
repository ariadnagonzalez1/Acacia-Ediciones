@extends('layouts.public')

@section('title', $promocion->nombre . ' | Acacia Ediciones')

@section('content')

<section class="public-section">

    <div class="public-container">

        <a
            href="{{ route('inicio') }}"
            class="public-back-link"
        >
            ← Volver al inicio
        </a>


        <div class="kit-detail">

            <div class="kit-detail-visual">

                <span>
                    KIT
                </span>

                <h2>
                    {{ $promocion->nombre }}
                </h2>

            </div>


            <div class="kit-detail-info">

                <span class="public-eyebrow">
                    Kit promocional
                </span>

                <h1>
                    {{ $promocion->nombre }}
                </h1>


                @if($promocion->mensaje)

                    <p class="kit-detail-description">
                        {{ $promocion->mensaje }}
                    </p>

                @endif


                <div class="kit-detail-books">

                    <h2>
                        Incluye
                    </h2>

                    @foreach($promocion->libros as $libro)

                        <a
                            href="{{ route('libro.show', $libro) }}"
                            class="kit-detail-book"
                        >

                            @if($libro->portada)

                                <img
                                    src="{{ asset(
                                        'storage/' . $libro->portada
                                    ) }}"
                                    alt="{{ $libro->titulo }}"
                                >

                            @endif

                            <div>

                                <strong>
                                    {{ $libro->titulo }}
                                </strong>

                                <span>
                                    {{ $libro->autor }}
                                </span>

                            </div>

                        </a>

                    @endforeach

                </div>


                <div class="book-detail-purchase">

                    <strong class="book-detail-price">

                        $ {{ number_format(
                            $promocion->precio_kit,
                            0,
                            ',',
                            '.'
                        ) }}

                    </strong>


                    <form
                        action="{{ route(
                            'carrito.kit.agregar',
                            $promocion
                        ) }}"
                        method="POST"
                        class="add-kit-to-cart-form"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="public-primary-button"
                        >
                            Comprar kit
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection