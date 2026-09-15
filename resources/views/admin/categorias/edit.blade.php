@extends('layouts.admin')

@section('title', 'Editar categoría | Acacia Ediciones')

@section('content')

<section class="admin-page">

    <div class="admin-page-header">

        <div>

            <span class="admin-eyebrow">
                Organización
            </span>

            <h1>
                Editar categoría
            </h1>

            <p>
                Modificá los datos de la categoría.
            </p>

        </div>

        <a
            href="{{ route('admin.categorias.index') }}"
            class="admin-secondary-button"
        >
            Volver
        </a>

    </div>


    <section class="admin-section">

        <form
            action="{{ route('admin.categorias.update', $categoria) }}"
            method="POST"
            class="admin-form category-edit-form"
        >

            @csrf
            @method('PUT')


            <div class="form-group">

                <label for="nombre">
                    Nombre
                </label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    value="{{ old('nombre', $categoria->nombre) }}"
                    required
                >

                @error('nombre')

                    <span class="form-error">
                        {{ $message }}
                    </span>

                @enderror

            </div>


            <div class="form-group">

                <label for="descripcion">
                    Descripción
                </label>

                <textarea
                    id="descripcion"
                    name="descripcion"
                    rows="6"
                >{{ old('descripcion', $categoria->descripcion) }}</textarea>

                @error('descripcion')

                    <span class="form-error">
                        {{ $message }}
                    </span>

                @enderror

            </div>


            <div class="admin-form-actions">

                <a
                    href="{{ route('admin.categorias.index') }}"
                    class="admin-secondary-button"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="admin-primary-button"
                >
                    Guardar cambios
                </button>

            </div>

        </form>

    </section>

</section>

@endsection