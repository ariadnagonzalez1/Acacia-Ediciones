@extends('layouts.public')

@section('title', 'Inicio | Acacia Ediciones')

@section('content')

<section class="public-hero">

    <div class="public-container public-hero-grid">

        <div class="public-hero-content">

            <span class="public-eyebrow">
                Editorial digital
            </span>

            <h1>
                Libros que llegan a tu correo,
                no a tu puerta.
            </h1>

            <p>
                Elegís, pagás online y recibís
                tus ebooks directamente en tu correo.
            </p>

            <div class="public-hero-actions">

                <a
                    href="{{ route('catalogo') }}"
                    class="public-primary-button"
                >
                    Ver catálogo
                </a>

                <a
                    href="#como-funciona"
                    class="public-secondary-button"
                >
                    Cómo funciona
                </a>

            </div>

        </div>


        <div class="public-hero-logo">

            <img
                src="{{ asset('images/logo.png') }}"
                alt="Acacia Ediciones"
            >

        </div>

    </div>

</section>


@if($promociones->isNotEmpty())

<section class="public-section">

    <div class="public-container">

        <div class="public-section-header">

            <div>

                <span class="public-eyebrow">
                    Promociones
                </span>

                <h2>
                    Lecturas especiales
                </h2>

            </div>

        </div>


        <div class="public-promo-grid">

            @foreach($promociones as $promocion)

                <article class="public-promo-card">

                    <span class="public-promo-type">

                        {{ $promocion->tipo === 'kit'
                            ? 'Kit'
                            : 'Descuento'
                        }}

                    </span>

                    <h3>
                        {{ $promocion->nombre }}
                    </h3>

                    @if($promocion->mensaje)

                        <p>
                            {{ $promocion->mensaje }}
                        </p>

                    @endif


                    @if($promocion->tipo === 'kit')

                        <strong class="public-promo-price">
                            $ {{ number_format(
                                $promocion->precio_kit,
                                0,
                                ',',
                                '.'
                            ) }}
                        </strong>

                        <div class="public-promo-books">

                            @foreach(
                                $promocion->libros
                                as $libro
                            )

                                <span>
                                    {{ $libro->titulo }}
                                </span>

                            @endforeach

                        </div>


                                                <form
                            action="{{ route('carrito.kit.agregar', $promocion) }}"
                            method="POST"
                            class="add-kit-to-cart-form"
                            data-title="{{ $promocion->nombre }}"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="public-primary-button"
                            >
                                Comprar kit
                            </button>
                        </form>

                    @else

                        <strong class="public-promo-price">
                            {{ number_format(
                                $promocion->porcentaje_descuento,
                                0
                            ) }}% OFF
                        </strong>

                    @endif

                </article>

            @endforeach

        </div>

    </div>

</section>

@endif


<section class="public-section public-section-soft">

    <div class="public-container">

        <div class="public-section-header">

            <div>

                <span class="public-eyebrow">
                    Los más elegidos
                </span>

                <h2>
                    Favoritos de los lectores
                </h2>

            </div>

            <a
                href="{{ route('catalogo') }}"
                class="public-view-all-button"
            >
                Ver todos
            </a>

        </div>


        <div class="public-books-grid">

            @forelse($librosDestacados as $libro)

                <x-public.libro-card
                    :libro="$libro"
                />

            @empty

                <div class="empty-state">
                    <p>
                        Todavía no hay libros destacados.
                    </p>
                </div>

            @endforelse

        </div>

    </div>

</section>


<section
    class="public-section"
    id="como-funciona"
>

    <div class="public-container">

        <div class="public-section-header">

            <div>

                <span class="public-eyebrow">
                    Cómo funciona
                </span>

                <h2>
                    Todo digital, todo por correo
                </h2>

                <p>
                    Cada compra se entrega en PDF
                    al correo indicado durante el checkout.
                </p>

            </div>

        </div>


        <div class="public-steps-grid">

            <article class="public-step">

                <div class="public-step-icon">
                    <i class="bi bi-book"></i>
                </div>

                <span>
                    Paso 1
                </span>

                <h3>
                    Elegís tus libros
                </h3>

                <p>
                    Sumás al carrito todos los ebooks
                    que quieras.
                </p>

            </article>


            <article class="public-step">

                <div class="public-step-icon">
                    <i class="bi bi-credit-card"></i>
                </div>

                <span>
                    Paso 2
                </span>

                <h3>
                    Pagás online
                </h3>

                <p>
                    El pago se realiza mediante
                    Mercado Pago.
                </p>

            </article>


            <article class="public-step">

                <div class="public-step-icon">
                    <i class="bi bi-envelope-paper"></i>
                </div>

                <span>
                    Paso 3
                </span>

                <h3>
                    Te llega el correo
                </h3>

                <p>
                    Una vez acreditado el pago,
                    recibís los PDF automáticamente.
                </p>

            </article>


            <article class="public-step">

                <div class="public-step-icon">
                    <i class="bi bi-tablet-landscape"></i>
                </div>

                <span>
                    Paso 4
                </span>

                <h3>
                    Leés donde quieras
                </h3>

                <p>
                    Descargás tus ebooks y los leés
                    en cualquier dispositivo.
                </p>

            </article>

        </div>

    </div>

</section>

@endsection