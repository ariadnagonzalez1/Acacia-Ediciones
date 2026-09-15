@extends('layouts.admin')

@section('title', 'Nuevo libro | Acacia Ediciones')

@section('content')

<section class="admin-page">

    <div class="admin-page-header">

        <div>
            <span class="admin-eyebrow">
                Catálogo
            </span>

            <h1>
                Nuevo libro
            </h1>

            <p>
                Cargá un nuevo ebook al catálogo.
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
            action="{{ route('admin.libros.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="admin-form"
        >

            @csrf

            @include('admin.libros._form', [
                'libro' => null
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
                    Guardar libro
                </button>

            </div>

        </form>

    </section>

</section>

@endsection