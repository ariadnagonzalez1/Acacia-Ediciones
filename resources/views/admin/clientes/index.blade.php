@extends('layouts.admin')

@section('title', 'Clientes | Acacia Ediciones')

@section('content')

<section class="admin-page">

    {{-- HEADER --}}

    <div class="admin-page-header">

        <div>

            <span class="admin-eyebrow">
                Clientes
            </span>

            <h1>
                Clientes
            </h1>

            <p>
                Consultá los datos de quienes compraron en Acacia Ediciones.
            </p>

        </div>

    </div>


    {{-- MÉTRICAS --}}

    <div class="client-stats-grid">

        <x-admin.stat-card
            titulo="Clientes"
            :valor="$cantidadClientes"
        />

    </div>


    {{-- LISTADO --}}

    <section class="admin-section">

        <div class="clients-toolbar">

            <div>

                <h2>
                    Listado de clientes
                </h2>

                <p>
                    Datos registrados a partir de compras realizadas.
                </p>

            </div>


            <form
                action="{{ route('admin.clientes.index') }}"
                method="GET"
                class="clients-search"
            >

                <input
                    type="search"
                    name="buscar"
                    value="{{ request('buscar') }}"
                    placeholder="Buscar nombre, correo, DNI o teléfono..."
                >

                <button
                    type="submit"
                    class="admin-primary-button"
                >
                    Buscar
                </button>


                @if(request('buscar'))

                    <a
                        href="{{ route('admin.clientes.index') }}"
                        class="admin-secondary-button"
                    >
                        Limpiar
                    </a>

                @endif

            </form>

        </div>


        @if($clientes->isEmpty())

            <div class="empty-state">

                <h3>

                    @if(request('buscar'))
                        No encontramos clientes
                    @else
                        Todavía no hay clientes
                    @endif

                </h3>

                <p>

                    @if(request('buscar'))
                        Probá con otro nombre, correo, DNI o teléfono.
                    @else
                        Cuando alguien compre un ebook, aparecerá acá.
                    @endif

                </p>

            </div>

        @else

            <div class="admin-table-wrapper">

                <table class="admin-table clients-table">

                    <thead>

                        <tr>
                            <th>Cliente</th>
                            <th>Documento</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Compras</th>
                            <th>Última compra</th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach($clientes as $cliente)

                            <tr>

                                {{-- NOMBRE --}}

                                <td>

                                    <div class="table-user">

                                        <strong>
                                            {{ $cliente->nombre }}
                                        </strong>

                                    </div>

                                </td>


                                {{-- DOCUMENTO --}}

                                <td>
                                    {{ $cliente->documento ?? '—' }}
                                </td>


                                {{-- TELÉFONO --}}

                                <td>

                                    @if($cliente->telefono)

                                        <a
                                            href="tel:{{ $cliente->telefono }}"
                                            class="client-contact-link"
                                        >
                                            {{ $cliente->telefono }}
                                        </a>

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- EMAIL --}}

                                <td>

                                    <a
                                        href="mailto:{{ $cliente->email }}"
                                        class="client-contact-link"
                                    >
                                        {{ $cliente->email }}
                                    </a>

                                </td>


                                {{-- COMPRAS --}}

                                <td>

                                    <span class="client-purchase-count">

                                        {{ $cliente->ventas_count }}

                                        {{
                                            $cliente->ventas_count === 1
                                                ? 'compra'
                                                : 'compras'
                                        }}

                                    </span>

                                </td>


                                {{-- ÚLTIMA COMPRA --}}

                                <td>

                                    @if($cliente->ventas_max_fecha_pago)

                                        {{ \Carbon\Carbon::parse(
                                            $cliente->ventas_max_fecha_pago
                                        )->format('d/m/Y') }}

                                    @else

                                        —

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            <div class="admin-pagination">

                {{ $clientes->links() }}

            </div>

        @endif

    </section>

</section>

@endsection