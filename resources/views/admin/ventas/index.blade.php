@extends('layouts.admin')

@section('title', 'Ventas | Acacia Ediciones')

@section('content')

<section class="admin-page">

    {{-- HEADER --}}

    <div class="admin-page-header">

        <div>

            <span class="admin-eyebrow">
                Ventas
            </span>

            <h1>
                Ventas y recaudación
            </h1>

            <p>
                Consultá las compras acreditadas y los ebooks vendidos.
            </p>

        </div>

    </div>


    {{-- MÉTRICAS --}}

    <div class="sales-stats-grid">

        <x-admin.stat-card
            titulo="Recaudado"
            :valor="'$ ' . number_format(
                $recaudado,
                0,
                ',',
                '.'
            )"
        />

        <x-admin.stat-card
            titulo="Ventas"
            :valor="$cantidadVentas"
        />

        <x-admin.stat-card
            titulo="Títulos vendidos"
            :valor="$titulosVendidos"
        />

    </div>


    {{-- LISTADO --}}

    <section class="admin-section">

        <div class="sales-toolbar">

            <div>

                <h2>
                    Historial de ventas
                </h2>

                <p>
                    Solo se muestran compras que fueron acreditadas.
                </p>

            </div>


            {{-- BUSCADOR --}}

            <form
                action="{{ route('admin.ventas.index') }}"
                method="GET"
                class="sales-search"
            >

                <input
                    type="search"
                    name="buscar"
                    value="{{ request('buscar') }}"
                    placeholder="Buscar orden, cliente o correo..."
                >

                <button
                    type="submit"
                    class="admin-primary-button"
                >
                    Buscar
                </button>


                @if(request('buscar'))

                    <a
                        href="{{ route('admin.ventas.index') }}"
                        class="admin-secondary-button"
                    >
                        Limpiar
                    </a>

                @endif

            </form>

        </div>


        @if($ventas->isEmpty())

            <div class="empty-state">

                <h3>

                    @if(request('buscar'))
                        No encontramos ventas
                    @else
                        Todavía no hay ventas
                    @endif

                </h3>

                <p>

                    @if(request('buscar'))

                        Probá con otro número de orden,
                        cliente o correo.

                    @else

                        Cuando Mercado Pago acredite
                        una compra, aparecerá acá.

                    @endif

                </p>

            </div>

        @else

            <div class="admin-table-wrapper">

                <table class="admin-table sales-table">

                    <thead>

                        <tr>
                            <th>Orden</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Correo de entrega</th>
                            <th>Títulos</th>
                            <th>Total</th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach($ventas as $venta)

                            <tr>

                                {{-- ORDEN --}}

                                <td>

                                    <strong class="sales-order">
                                        {{ $venta->numero_orden }}
                                    </strong>

                                </td>


                                {{-- FECHA --}}

                                <td>

                                    <div class="sales-date">

                                        <span>
                                            {{ $venta->fecha_pago?->format('d/m/Y') }}
                                        </span>

                                        <small>
                                            {{ $venta->fecha_pago?->format('H:i') }}
                                        </small>

                                    </div>

                                </td>


                                {{-- CLIENTE --}}

                                <td>

                                    <div class="table-user">

                                        <strong>
                                            {{ $venta->cliente->nombre }}
                                        </strong>

                                        @if($venta->cliente->documento)

                                            <span>
                                                DNI:
                                                {{ $venta->cliente->documento }}
                                            </span>

                                        @endif

                                    </div>

                                </td>


                                {{-- EMAIL --}}

                                <td>

                                    <a
                                        href="mailto:{{ $venta->cliente->email }}"
                                        class="sales-email"
                                    >
                                        {{ $venta->cliente->email }}
                                    </a>

                                </td>


                                {{-- LIBROS --}}

                                <td>

                                    <div class="sales-books">

                                        <span class="sales-books-count">

                                            {{ $venta->detalles->count() }}

                                            {{
                                                $venta->detalles->count() === 1
                                                    ? 'título'
                                                    : 'títulos'
                                            }}

                                        </span>


                                        <div class="sales-book-list">

                                            @foreach(
                                                $venta->detalles
                                                as $detalle
                                            )

                                                <span>
                                                    {{ $detalle->libro->titulo }}
                                                </span>

                                            @endforeach

                                        </div>

                                    </div>

                                </td>


                                {{-- TOTAL --}}

                                <td class="table-price">

                                    $ {{ number_format(
                                        $venta->total,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- PAGINACIÓN --}}

            <div class="admin-pagination">

                {{ $ventas->links() }}

            </div>

        @endif

    </section>

</section>

@endsection