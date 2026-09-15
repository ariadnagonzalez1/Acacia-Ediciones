@extends('layouts.admin')

@section('title', 'Editar libro | Acacia Ediciones')

@section('content')

<section class="admin-page">

    <div class="admin-page-header">

        <div>
            <span class="admin-eyebrow">
                Catálogo
            </span>

            <h1>
                Editar libro
            </h1>

            <p>
                Modificá la información del ebook.
            </p>
        </div>

        <a
            href="{{ route('admin.libros.index') }}"
            class="admin-secondary-button"
        >
            Volver
        </a>

    </div>


    <section class="admin-section">

        <form
            action="{{ route('admin.libros.update', $libro) }}"
            method="POST"
            enctype="multipart/form-data"
            class="admin-form"
        >

            @csrf
            @method('PUT')

            @include('admin.libros._form', [
                'libro' => $libro
            ])

            <div class="admin-form-actions">

                <a
                    href="{{ route('admin.libros.index') }}"
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