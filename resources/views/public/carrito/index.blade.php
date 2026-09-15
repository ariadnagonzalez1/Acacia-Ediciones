@extends('layouts.public')

@section('title', 'Carrito | Acacia Ediciones')

@section('content')

<section class="public-section cart-page">

    <div class="public-container">

        <div class="public-section-header">

            <div>

                <span class="public-eyebrow">
                    Compra
                </span>

                <h1>
                    Tu carrito
                </h1>

                <p>
                    Revisá los ebooks que elegiste antes de continuar con la compra.
                </p>

            </div>

        </div>


        @if(empty($carrito))

            <div class="empty-state cart-empty-state">

                <h3>
                    Tu carrito está vacío
                </h3>

                <p>
                    Todavía no agregaste ningún ebook.
                </p>

                <a
                    href="{{ route('catalogo') }}"
                    class="public-primary-button"
                >
                    Ver catálogo
                </a>

            </div>

        @else

            @php

                $cantidadProductos = collect($carrito)
                    ->sum(function ($item) {
                        return $item['cantidad'] ?? 1;
                    });

            @endphp


            <div class="cart-layout">


                {{-- =========================================
                     PRODUCTOS
                ========================================== --}}

                <section class="cart-items">

                    @foreach($carrito as $clave => $item)

                        <article class="cart-item">


                            {{-- =========================
                                 INFORMACIÓN PRINCIPAL
                            ========================== --}}

                            <div class="cart-item-main">


                                {{-- PORTADA --}}

                                <div class="cart-item-cover">

                                    @if(
                                        ($item['tipo'] ?? null) === 'libro'
                                        && !empty($item['portada'])
                                    )

                                        <img
                                            src="{{ asset(
                                                'storage/' . $item['portada']
                                            ) }}"
                                            alt="{{ $item['titulo'] }}"
                                        >

                                    @else

                                        <div class="cart-kit-placeholder">

                                            <span>
                                                KIT
                                            </span>

                                        </div>

                                    @endif

                                </div>


                                {{-- INFORMACIÓN --}}

                                <div class="cart-item-info">

                                    <span class="cart-item-type">

                                        {{
                                            ($item['tipo'] ?? null) === 'kit'
                                                ? 'Kit promocional'
                                                : 'Ebook'
                                        }}

                                    </span>


                                    <h2>
                                        {{ $item['titulo'] }}
                                    </h2>


                                    {{-- LIBRO INDIVIDUAL --}}

                                    @if(
                                        ($item['tipo'] ?? null)
                                        === 'libro'
                                    )

                                        @if(!empty($item['autor']))

                                            <p class="cart-item-author">
                                                {{ $item['autor'] }}
                                            </p>

                                        @endif


                                    {{-- KIT --}}

                                    @else

                                        @if(!empty($item['libros']))

                                            <div class="cart-kit-books">

                                                @foreach(
                                                    $item['libros']
                                                    as $titulo
                                                )

                                                    <span>
                                                        {{ $titulo }}
                                                    </span>

                                                @endforeach

                                            </div>

                                        @endif

                                    @endif

                                </div>

                            </div>


                            {{-- =========================
                                 CANTIDAD + PRECIO + QUITAR
                            ========================== --}}

                            <div class="cart-item-side">


                                {{-- CANTIDAD --}}

                                <div class="cart-quantity">


                                    {{-- RESTAR --}}

                                    <form
                                        action="{{ route(
                                            'carrito.cantidad',
                                            $clave
                                        ) }}"
                                        method="POST"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <input
                                            type="hidden"
                                            name="operacion"
                                            value="decrementar"
                                        >

                                        <button
                                            type="submit"
                                            class="cart-quantity-button"
                                            aria-label="Restar una unidad"
                                        >
                                            −
                                        </button>

                                    </form>


                                    {{-- CANTIDAD ACTUAL --}}

                                    <span class="cart-quantity-value">

                                        {{ $item['cantidad'] ?? 1 }}

                                    </span>


                                    {{-- SUMAR --}}

                                    <form
                                        action="{{ route(
                                            'carrito.cantidad',
                                            $clave
                                        ) }}"
                                        method="POST"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <input
                                            type="hidden"
                                            name="operacion"
                                            value="incrementar"
                                        >

                                        <button
                                            type="submit"
                                            class="cart-quantity-button"
                                            aria-label="Agregar una unidad"
                                        >
                                            +
                                        </button>

                                    </form>

                                </div>


                                {{-- PRECIO --}}

                                <strong class="cart-item-price">

                                    $ {{ number_format(
                                        $item['subtotal'],
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </strong>


                                {{-- QUITAR --}}

                                <form
                                    action="{{ route(
                                        'carrito.eliminar',
                                        $clave
                                    ) }}"
                                    method="POST"
                                    class="cart-remove-form"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="cart-remove-button"
                                    >
                                        Quitar
                                    </button>

                                </form>

                            </div>

                        </article>

                    @endforeach

                </section>


                {{-- =========================================
                     RESUMEN
                ========================================== --}}

                <aside class="cart-summary">

                    <h2>
                        Resumen
                    </h2>


                    <div class="cart-summary-row">

                        <span>
                            Productos
                        </span>

                        <strong>
                            {{ $cantidadProductos }}
                        </strong>

                    </div>


                    <div class="cart-summary-row">

                        <span>
                            Entrega
                        </span>

                        <strong>
                            Digital
                        </strong>

                    </div>


                    <div class="cart-summary-divider"></div>


                    <div class="cart-summary-total">

                        <span>
                            Total
                        </span>

                        <strong>

                            $ {{ number_format(
                                $total,
                                0,
                                ',',
                                '.'
                            ) }}

                        </strong>

                    </div>


                    {{-- CONTINUAR --}}

                    <a
                        href="{{ route('checkout.index') }}"
                        class="
                            public-primary-button
                            cart-checkout-button
                        "
                    >
                        Continuar la compra
                    </a>


                    {{-- VACIAR CARRITO --}}

                    <form
                        action="{{ route('carrito.vaciar') }}"
                        method="POST"
                        class="cart-clear-form"
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="cart-clear-button"
                        >
                            Vaciar carrito
                        </button>

                    </form>


                    {{-- INFORMACIÓN --}}

                    <div class="cart-summary-note">

                        <span>
                            ✉
                        </span>

                        <p>
                            Los ebooks se envían automáticamente al correo
                            que cargues en el paso siguiente.
                        </p>

                    </div>

                </aside>

            </div>

        @endif

    </div>

</section>

@endsection