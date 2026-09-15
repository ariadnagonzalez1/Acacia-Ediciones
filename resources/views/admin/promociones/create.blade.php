@extends('layouts.admin')

@section('title', 'Nueva promoción | Acacia Ediciones')

@section('content')

<section class="admin-page">

    <div class="admin-page-header">

        <div>

            <span class="admin-eyebrow">
                Ventas
            </span>

            <h1>
                Nueva promoción
            </h1>

            <p>
                Creá un descuento o un kit de libros.
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
            action="{{ route('admin.promociones.store') }}"
            method="POST"
            class="admin-form"
        >
            @csrf

            @include('admin.promociones._form', [
                'promocion' => null
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
                    Crear promoción
                </button>

            </div>

        </form>

    </section>

</section>

@endsection