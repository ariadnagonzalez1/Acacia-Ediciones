@extends('layouts.admin')

@section(
    'title',
    'Categorías | Acacia Ediciones'
)

@section('content')


<section class="admin-page">


    {{-- HEADER DE PÁGINA --}}

    <div class="admin-page-header">


        <div>

            <span class="admin-eyebrow">
                Organización
            </span>

            <h1>
                Categorías
            </h1>

            <p>
                Las categorías son los filtros
                que ven los lectores en el catálogo.
            </p>

        </div>


    </div>


    <div class="categories-layout">


        {{-- =========================================
             LISTADO DE CATEGORÍAS
        ========================================== --}}

        <section class="admin-section">


            <div class="admin-section-header">


                <div>

                    <h2>
                        Categorías disponibles
                    </h2>

                    <p>
                        Administrá las categorías
                        del catálogo.
                    </p>

                </div>


            </div>


            @if($categorias->isEmpty())


                <div class="empty-state">

                    <h3>
                        Todavía no hay categorías
                    </h3>

                    <p>
                        Creá la primera categoría
                        desde el formulario.
                    </p>

                </div>


            @else


                <div class="category-list">


                    @foreach($categorias as $categoria)


                        <article class="category-item">


                            {{-- INFO --}}

                            <div class="category-info">


                                <div class="category-title-row">


                                    <h3>
                                        {{ $categoria->nombre }}
                                    </h3>


                                    <span class="category-count">

                                        {{ $categoria->libros_count }}

                                        {{
                                            $categoria->libros_count === 1
                                                ? 'título'
                                                : 'títulos'
                                        }}

                                    </span>


                                </div>


                                @if($categoria->descripcion)

                                    <p>
                                        {{ $categoria->descripcion }}
                                    </p>

                                @endif


                            </div>


                            {{-- ACCIONES --}}

                            <div class="category-actions">


                                <a
                                    href="{{ route(
                                        'admin.categorias.edit',
                                        $categoria
                                    ) }}"
                                    class="
                                        table-action-button
                                        table-action-edit
                                    "
                                >
                                    Editar
                                </a>


                                <form
                                    action="{{ route(
                                        'admin.categorias.destroy',
                                        $categoria
                                    ) }}"
                                    method="POST"
                                    class="form-eliminar"
                                    data-nombre="{{ $categoria->nombre }}"
                                >

                                    @csrf
                                    @method('DELETE')


                                    <button
                                        type="submit"
                                        class="
                                            table-action-button
                                            table-action-delete
                                        "
                                    >
                                        Eliminar
                                    </button>


                                </form>


                            </div>


                        </article>


                    @endforeach


                </div>


            @endif


        </section>


        {{-- =========================================
             CREAR CATEGORÍA
        ========================================== --}}

        <section
            class="
                admin-section
                category-create-card
            "
        >


            <div class="admin-section-header">


                <div>

                    <h2>
                        Nueva categoría
                    </h2>

                    <p>
                        Creá una nueva categoría
                        para organizar los libros.
                    </p>

                </div>


            </div>


            <form
                action="{{ route(
                    'admin.categorias.store'
                ) }}"
                method="POST"
                class="admin-form"
            >

                @csrf


                {{-- NOMBRE --}}

                <div class="form-group">


                    <label for="nombre">
                        Nombre
                    </label>


                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        value="{{ old('nombre') }}"
                        placeholder="Ej: Crónica"
                        required
                    >


                    @error('nombre')

                        <span class="form-error">
                            {{ $message }}
                        </span>

                    @enderror


                </div>


                {{-- DESCRIPCIÓN --}}

                <div class="form-group">


                    <label for="descripcion">
                        Descripción
                    </label>


                    <textarea
                        id="descripcion"
                        name="descripcion"
                        rows="5"
                        placeholder="Qué tipo de títulos entran en esta categoría"
                    >{{ old('descripcion') }}</textarea>


                    @error('descripcion')

                        <span class="form-error">
                            {{ $message }}
                        </span>

                    @enderror


                </div>


                <button
                    type="submit"
                    class="
                        admin-primary-button
                        category-submit
                    "
                >
                    + Agregar categoría
                </button>


            </form>


        </section>


    </div>


</section>


@endsection