@extends('layouts.admin')

@section('title', 'Editar promoción | Acacia Ediciones')

@section('content')

<section class="admin-page">

    <div class="admin-page-header">

        <div>

            <span class="admin-eyebrow">
                Ventas
            </span>

            <h1>
                Editar promoción
            </h1>

            <p>
                Modificá los libros, precio, descuento o vigencia.
            </p>

        </div>

        <a
            href="{{ route('admin.promociones.index') }}"
            class="admin-secondary-button"
        >
            Volver
        </a>

    </div>


    <section class="admin-section">

        <form
            action="{{ route('admin.promociones.update', $promocion) }}"
            method="POST"
            class="admin-form"
        >
            @csrf
            @method('PUT')

            @include('admin.promociones._form', [
                'promocion' => $promocion
            ])


            <div class="admin-form-actions">

                <a
                    href="{{ route('admin.promociones.index') }}"
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