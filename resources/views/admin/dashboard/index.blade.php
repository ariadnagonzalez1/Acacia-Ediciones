@extends('layouts.admin')

@section('title', 'Resumen | Acacia Ediciones')

@section('content')

    <section class="admin-page">

        <div class="admin-page-header">

            <div>

                <span class="admin-eyebrow">
                    Administración
                </span>

                <h1>
                    Resumen
                </h1>

                <p>
                    Vista general de Acacia Ediciones.
                </p>

            </div>

        </div>


        <div class="stats-grid">

            <x-admin.stat-card
                titulo="Recaudado"
                :valor="'$ ' . number_format($recaudado, 0, ',', '.')"
            />

            <x-admin.stat-card
                titulo="Ventas"
                :valor="$cantidadVentas"
            />

            <x-admin.stat-card
                titulo="Títulos publicados"
                :valor="$titulosPublicados"
            />

            <x-admin.stat-card
                titulo="Correos guardados"
                :valor="$correosGuardados"
            />

        </div>


        <section class="admin-section">

            <div class="admin-section-header">

                <div>
                    <h2>
                        Últimas ventas
                    </h2>

                    <p>
                        Compras acreditadas recientemente.
                    </p>
                </div>

                <a
                    href="{{ route('admin.ventas.index') }}"
                    class="admin-link-button"
                >
                    Ver todas
                </a>

            </div>


            @if($ultimasVentas->isEmpty())

                <div class="empty-state">

                    <h3>
                        Todavía no hay ventas
                    </h3>

                    <p>
                        Cuando se acrediten compras van a aparecer acá.
                    </p>

                </div>

            @else

                <div class="admin-table-wrapper">

                    <table class="admin-table">

                        <thead>

                            <tr>
                                <th>Orden</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Títulos</th>
                                <th>Total</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($ultimasVentas as $venta)

                                <tr>

                                    <td>
                                        {{ $venta->numero_orden }}
                                    </td>

                                    <td>
                                        {{ $venta->fecha_pago?->format('d/m/Y') }}
                                    </td>

                                    <td>

                                        <div class="table-user">

                                            <strong>
                                                {{ $venta->cliente->nombre }}
                                            </strong>

                                            <span>
                                                {{ $venta->cliente->email }}
                                            </span>

                                        </div>

                                    </td>

                                    <td>
                                        {{ $venta->detalles->count() }}
                                    </td>

                                    <td class="table-price">
                                        $ {{ number_format($venta->total, 0, ',', '.') }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </section>

    </section>

@endsection