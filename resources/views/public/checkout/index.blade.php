@extends('layouts.public')

@section('title', 'Finalizar compra | Acacia Ediciones')

@section('content')

<section class="public-section checkout-page">

    <div class="public-container">

        <a
            href="{{ route('carrito.index') }}"
            class="public-back-link"
        >
            ← Volver al carrito
        </a>

        <div class="public-section-header checkout-header">

            <div>

                <span class="public-eyebrow">
                    Paso final
                </span>

                <h1>
                    Finalizar compra
                </h1>

                <p>
                    Completá tus datos para recibir los ebooks por correo.
                </p>

            </div>

        </div>


        <div class="checkout-layout">


            {{-- FORMULARIO --}}

            <section class="checkout-form-card">

                <h2>
                    Tus datos
                </h2>

                <p class="checkout-form-description">
                    Usaremos este correo para enviarte los PDF cuando el pago se acredite.
                </p>


                <form
                    action="#"
                    method="POST"
                    class="checkout-form"
                >

                    @csrf


                    <div class="checkout-form-grid">


                        <div class="form-group">

                            <label for="nombre">
                                Nombre y apellido
                            </label>

                            <input
                                type="text"
                                id="nombre"
                                name="nombre"
                                value="{{ old('nombre') }}"
                                placeholder="Ej: Lucía Méndez"
                                required
                            >

                            @error('nombre')
                                <span class="form-error">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>


                        <div class="form-group">

                            <label for="documento">
                                Documento
                            </label>

                            <input
                                type="text"
                                id="documento"
                                name="documento"
                                value="{{ old('documento') }}"
                                placeholder="Ej: 30.123.456"
                                required
                            >

                            @error('documento')
                                <span class="form-error">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>


                        <div class="form-group checkout-form-full">

                            <label for="email">
                                Correo donde querés recibir los PDF
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="vos@correo.com"
                                required
                            >

                            @error('email')
                                <span class="form-error">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>


                        <div class="form-group checkout-form-full">

                            <label for="telefono">
                                Teléfono
                                <span class="form-optional">
                                    (opcional)
                                </span>
                            </label>

                            <input
                                type="text"
                                id="telefono"
                                name="telefono"
                                value="{{ old('telefono') }}"
                                placeholder="+54 11 5555 5555"
                            >

                            @error('telefono')
                                <span class="form-error">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>


                    </div>


                    <div class="checkout-payment-box">

                        <div class="checkout-payment-icon">
                            $
                        </div>

                        <div>

                            <strong>
                                Pago mediante Mercado Pago
                            </strong>

                            <p>
                                Tarjeta de crédito, débito o dinero disponible en cuenta.
                            </p>

                        </div>

                    </div>


                    <button
                        type="submit"
                        class="public-primary-button checkout-submit-button"
                    >
                        Pagar con Mercado Pago
                    </button>

                </form>

            </section>


            {{-- RESUMEN --}}

            <aside class="checkout-summary">

                <h2>
                    Tu pedido
                </h2>


                <div class="checkout-summary-items">

                    @foreach($carrito as $item)

                        <div class="checkout-summary-item">

                            <div>

                                <strong>
                                    {{ $item['titulo'] }}
                                </strong>

                                @if(($item['cantidad'] ?? 1) > 1)

                                    <span>
                                        x {{ $item['cantidad'] }}
                                    </span>

                                @endif

                            </div>

                            <strong>
                                $ {{ number_format(
                                    $item['subtotal'],
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </strong>

                        </div>

                    @endforeach

                </div>


                <div class="checkout-summary-divider"></div>


                <div class="checkout-summary-total">

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


                <div class="checkout-summary-note">

                    <span>
                        ✉
                    </span>

                    <p>
                        Cuando Mercado Pago confirme el pago, los ebooks se enviarán automáticamente al correo indicado.
                    </p>

                </div>

            </aside>

        </div>

    </div>

</section>

@endsection