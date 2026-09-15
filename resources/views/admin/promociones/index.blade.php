@extends('layouts.admin')

@section('title', 'Promociones | Acacia Ediciones')

@section('content')

<section class="admin-page">

    <div class="admin-page-header">

        <div>
            <span class="admin-eyebrow">
                Ventas
            </span>

            <h1>
                Promociones
            </h1>

            <p>
                Creá descuentos y kits especiales para el catálogo.
            </p>
        </div>

        <a
            href="{{ route('admin.promociones.create') }}"
            class="admin-link-button"
        >
            + Nueva promoción
        </a>

    </div>


    @if($promociones->isEmpty())

        <section class="admin-section">

            <div class="empty-state">

                <h3>
                    Todavía no hay promociones
                </h3>

                <p>
                    Creá una promoción para ofrecer descuentos o kits de ebooks.
                </p>

            </div>

        </section>

    @else

        <div class="promotion-grid">

            @foreach($promociones as $promocion)

                <article class="promotion-card">

                    <div class="promotion-card-header">

                        <div>

                            <div class="promotion-title-row">

                                <h2>
                                    {{ $promocion->nombre }}
                                </h2>

                                @if($promocion->tipo === 'descuento')

                                    <span class="promotion-discount">
                                        -{{ number_format($promocion->porcentaje_descuento, 0) }}%
                                    </span>

                                @else

                                    <span class="promotion-kit-badge">
                                        Kit
                                    </span>

                                @endif

                            </div>


                            <div class="promotion-status-row">

                                <span class="
                                    status-badge
                                    {{ $promocion->activa ? 'status-published' : 'status-draft' }}
                                ">
                                    {{ $promocion->activa ? 'Activa' : 'Pausada' }}
                                </span>

                                @if($promocion->mostrar_inicio)

                                    <span class="status-badge status-featured">
                                        Visible en inicio
                                    </span>

                                @endif

                            </div>

                        </div>


                        <div class="promotion-actions">

                            <a
                                href="{{ route('admin.promociones.edit', $promocion) }}"
                                class="table-action-button table-action-edit"
                            >
                                Editar
                            </a>


                            <form
                                action="{{ route('admin.promociones.destroy', $promocion) }}"
                                method="POST"
                                class="form-eliminar"
                                data-nombre="{{ $promocion->nombre }}"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="table-action-button table-action-delete"
                                >
                                    Eliminar
                                </button>

                            </form>

                        </div>

                    </div>


                    @if($promocion->mensaje)

                        <p class="promotion-description">
                            {{ $promocion->mensaje }}
                        </p>

                    @endif


                    <div class="promotion-data">

                        @if($promocion->tipo === 'kit')

                            <div>
                                <span>Precio del kit</span>

                                <strong>
                                    $ {{ number_format($promocion->precio_kit, 0, ',', '.') }}
                                </strong>
                            </div>

                        @else

                            <div>
                                <span>Descuento</span>

                                <strong>
                                    {{ number_format($promocion->porcentaje_descuento, 0) }}%
                                </strong>
                            </div>

                        @endif


                        <div>
                            <span>Libros incluidos</span>

                            <strong>
                                {{ $promocion->libros->count() }}
                            </strong>
                        </div>


                        <div>
                            <span>Hasta</span>

                            <strong>
                                {{ $promocion->fecha_fin?->format('d/m/Y') ?? 'Sin límite' }}
                            </strong>
                        </div>

                    </div>


                    <div class="promotion-books">

                        <span class="promotion-books-title">
                            Libros incluidos
                        </span>

                        <div class="promotion-book-tags">

                            @foreach($promocion->libros as $libro)

                                <span class="promotion-book-tag">
                                    {{ $libro->titulo }}
                                </span>

                            @endforeach

                        </div>

                    </div>

                </article>

            @endforeach

        </div>

    @endif

</section>

@endsection