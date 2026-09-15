@extends('layouts.admin')

@section('title', 'Libros | Acacia Ediciones')

@section('content')

<section class="admin-page">

    <div class="admin-page-header">

        <div>
            <span class="admin-eyebrow">
                Catálogo
            </span>

            <h1>
                Libros
            </h1>

            <p>
                Administrá los ebooks disponibles en Acacia Ediciones.
            </p>
        </div>

        <a
            href="{{ route('admin.libros.create') }}"
            class="admin-link-button"
        >
            + Nuevo libro
        </a>

    </div>


    <section class="admin-section">

        @if($libros->isEmpty())

            <div class="empty-state">

                <h3>
                    Todavía no hay libros
                </h3>

                <p>
                    Cuando cargues tu primer ebook aparecerá acá.
                </p>

            </div>

        @else

            <div class="admin-table-wrapper">

                <table class="admin-table">

                    <thead>
                        <tr>
                            <th>Portada</th>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Destacado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($libros as $libro)

                            <tr>

                                <td>
                                    <div class="book-cover-table">

                                        @if($libro->portada)

                                            <img
                                                src="{{ asset('storage/' . $libro->portada) }}"
                                                alt="{{ $libro->titulo }}"
                                            >

                                        @else

                                            <div class="book-cover-placeholder">
                                                Sin portada
                                            </div>

                                        @endif

                                    </div>
                                </td>

                                <td>
                                    <strong>
                                        {{ $libro->titulo }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $libro->autor }}
                                </td>

                                <td>
                                    {{ $libro->categoria?->nombre }}
                                </td>

                                <td class="table-price">
                                    $ {{ number_format($libro->precio, 0, ',', '.') }}
                                </td>

                                <td>

                                    <span class="status-badge {{ $libro->estado === 'publicado' ? 'status-published' : 'status-draft' }}">

                                        {{ ucfirst($libro->estado) }}

                                    </span>

                                </td>

                                <td>

                                    @if($libro->destacado)

                                        <span class="status-badge status-featured">
                                            Sí
                                        </span>

                                    @else

                                        <span class="status-badge">
                                            No
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <div class="table-actions">

    <a
        href="{{ route('admin.libros.edit', $libro) }}"
        class="table-action-button table-action-edit"
    >
        Editar
    </a>

    <form
    action="{{ route('admin.libros.destroy', $libro) }}"
    method="POST"
    class="form-eliminar"
    data-nombre="{{ $libro->titulo }}"
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

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            <div class="admin-pagination">
                {{ $libros->links() }}
            </div>

        @endif

    </section>

</section>

@endsection