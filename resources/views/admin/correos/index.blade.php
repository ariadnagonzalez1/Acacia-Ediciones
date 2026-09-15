@extends('layouts.admin')

@section('title', 'Correos automáticos | Acacia Ediciones')

@section('content')

<section class="admin-page">

    <div class="admin-page-header">

        <div>

            <span class="admin-eyebrow">
                Comunicación
            </span>

            <h1>
                Correos automáticos
            </h1>

            <p>
                Configurá los mensajes que se envían automáticamente desde Acacia Ediciones.
            </p>

        </div>

    </div>


    <div class="mail-settings-grid">

        @foreach($correos as $correo)

            <section class="admin-section mail-settings-card">

                <div class="mail-settings-header">

                    <div>

                        <span class="mail-type-badge">

                            @if($correo->tipo === 'entrega_compra')
                                Entrega de compra
                            @else
                                Promoción
                            @endif

                        </span>

                        <h2>

                            @if($correo->tipo === 'entrega_compra')
                                Correo de entrega del ebook
                            @else
                                Correo promocional
                            @endif

                        </h2>

                        <p>

                            @if($correo->tipo === 'entrega_compra')
                                Este mensaje se envía cuando una compra fue acreditada.
                            @else
                                Este mensaje se usa para avisar promociones a quienes aceptaron recibir novedades.
                            @endif

                        </p>

                    </div>

                </div>


                <form
                    action="{{ route('admin.correos.update', $correo) }}"
                    method="POST"
                    class="admin-form"
                >

                    @csrf
                    @method('PUT')


                    <div class="form-group">

                        <label for="asunto_{{ $correo->id }}">
                            Asunto
                        </label>

                        <input
                            type="text"
                            id="asunto_{{ $correo->id }}"
                            name="asunto"
                            value="{{ old('asunto', $correo->asunto) }}"
                            required
                        >

                        @error('asunto')
                            <span class="form-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    <div class="form-group">

                        <label for="mensaje_{{ $correo->id }}">
                            Mensaje
                        </label>

                        <textarea
                            id="mensaje_{{ $correo->id }}"
                            name="mensaje"
                            rows="8"
                            required
                        >{{ old('mensaje', $correo->mensaje) }}</textarea>

                        @error('mensaje')
                            <span class="form-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    <label class="mail-active-toggle">

                        <input
                            type="checkbox"
                            name="activo"
                            value="1"
                            @checked(old('activo', $correo->activo))
                        >

                        <div>

                            <strong>
                                Correo activo
                            </strong>

                            <span>
                                Si lo desactivás, este correo no se enviará automáticamente.
                            </span>

                        </div>

                    </label>


                    <div class="mail-settings-actions">

                        <button
                            type="submit"
                            class="admin-primary-button"
                        >
                            Guardar cambios
                        </button>

                    </div>

                </form>

            </section>

        @endforeach

    </div>

</section>

@endsection