<div class="admin-form-grid">

    {{-- NOMBRE --}}

    <div class="form-group">

        <label for="nombre">
            Nombre de la promoción
        </label>

        <input
            type="text"
            id="nombre"
            name="nombre"
            value="{{ old('nombre', $promocion?->nombre) }}"
            placeholder="Ej: Semana de la poesía"
            required
        >

        @error('nombre')
            <span class="form-error">
                {{ $message }}
            </span>
        @enderror

    </div>


    {{-- TIPO --}}

    <div class="form-group">

        <label for="tipo">
            Tipo de promoción
        </label>

        <select
            id="tipo"
            name="tipo"
            required
        >

            <option value="">
                Elegir tipo
            </option>

            <option
                value="descuento"
                @selected(
                    old('tipo', $promocion?->tipo) === 'descuento'
                )
            >
                Descuento
            </option>

            <option
                value="kit"
                @selected(
                    old('tipo', $promocion?->tipo) === 'kit'
                )
            >
                Kit de libros
            </option>

        </select>

        @error('tipo')
            <span class="form-error">
                {{ $message }}
            </span>
        @enderror

    </div>


    {{-- DESCUENTO --}}

    <div
        class="form-group promotion-discount-field"
        id="promotionDiscountField"
    >

        <label for="porcentaje_descuento">
            Descuento (%)
        </label>

        <input
            type="number"
            id="porcentaje_descuento"
            name="porcentaje_descuento"
            min="1"
            max="100"
            step="0.01"
            value="{{ old(
                'porcentaje_descuento',
                $promocion?->porcentaje_descuento
            ) }}"
            placeholder="15"
        >

        @error('porcentaje_descuento')
            <span class="form-error">
                {{ $message }}
            </span>
        @enderror

    </div>


    {{-- PRECIO KIT --}}

    <div
        class="form-group promotion-kit-field"
        id="promotionKitField"
    >

        <label for="precio_kit">
            Precio especial del kit
        </label>

        <input
            type="number"
            id="precio_kit"
            name="precio_kit"
            min="0"
            step="0.01"
            value="{{ old(
                'precio_kit',
                $promocion?->precio_kit
            ) }}"
            placeholder="12500"
        >

        @error('precio_kit')
            <span class="form-error">
                {{ $message }}
            </span>
        @enderror

    </div>


    {{-- FECHA INICIO --}}

    <div class="form-group">

        <label for="fecha_inicio">
            Vigente desde
        </label>

        <input
            type="date"
            id="fecha_inicio"
            name="fecha_inicio"
            value="{{ old(
                'fecha_inicio',
                $promocion?->fecha_inicio?->format('Y-m-d')
            ) }}"
        >

        @error('fecha_inicio')
            <span class="form-error">
                {{ $message }}
            </span>
        @enderror

    </div>


    {{-- FECHA FIN --}}

    <div class="form-group">

        <label for="fecha_fin">
            Vigente hasta
        </label>

        <input
            type="date"
            id="fecha_fin"
            name="fecha_fin"
            value="{{ old(
                'fecha_fin',
                $promocion?->fecha_fin?->format('Y-m-d')
            ) }}"
        >

        @error('fecha_fin')
            <span class="form-error">
                {{ $message }}
            </span>
        @enderror

    </div>


    {{-- MENSAJE --}}

    <div class="form-group admin-form-full">

        <label for="mensaje">
            Mensaje de la promoción
        </label>

        <textarea
            id="mensaje"
            name="mensaje"
            rows="5"
            placeholder="Contales de qué se trata la promoción..."
        >{{ old('mensaje', $promocion?->mensaje) }}</textarea>

        @error('mensaje')
            <span class="form-error">
                {{ $message }}
            </span>
        @enderror

    </div>


    {{-- LIBROS --}}

    <div class="form-group admin-form-full">

        <label>
            Libros incluidos
        </label>

        <p class="form-help">
            Seleccioná los libros que participarán de esta promoción.
        </p>


        @php

            $librosSeleccionados = old(
                'libros',
                $promocion
                    ? $promocion->libros->pluck('id')->toArray()
                    : []
            );

        @endphp


        <div class="promotion-book-selector">

            @forelse($libros as $libro)

                <label class="promotion-book-option">

                    <input
                        type="checkbox"
                        name="libros[]"
                        value="{{ $libro->id }}"
                        @checked(
                            in_array(
                                $libro->id,
                                $librosSeleccionados
                            )
                        )
                    >

                    <div class="promotion-book-option-content">

                        @if($libro->portada)

                            <img
                                src="{{ asset(
                                    'storage/' . $libro->portada
                                ) }}"
                                alt="{{ $libro->titulo }}"
                            >

                        @else

                            <div class="promotion-book-option-placeholder">
                                PDF
                            </div>

                        @endif


                        <div>

                            <strong>
                                {{ $libro->titulo }}
                            </strong>

                            <span>
                                {{ $libro->autor }}
                            </span>

                            <span class="promotion-book-price">
                                $ {{ number_format(
                                    $libro->precio,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </span>

                        </div>

                    </div>

                </label>

            @empty

                <div class="empty-state">
                    <p>
                        No hay libros publicados disponibles.
                    </p>
                </div>

            @endforelse

        </div>

        @error('libros')
            <span class="form-error">
                {{ $message }}
            </span>
        @enderror

        @error('libros.*')
            <span class="form-error">
                {{ $message }}
            </span>
        @enderror

    </div>


    {{-- CONFIGURACIÓN --}}

    <div class="form-group admin-form-full">

        <div class="promotion-settings">

            <label class="promotion-setting">

                <input
                    type="checkbox"
                    name="activa"
                    value="1"
                    @checked(
                        old(
                            'activa',
                            $promocion?->activa ?? true
                        )
                    )
                >

                <div>
                    <strong>
                        Promoción activa
                    </strong>

                    <span>
                        Los precios promocionales estarán disponibles.
                    </span>
                </div>

            </label>


            <label class="promotion-setting">

                <input
                    type="checkbox"
                    name="mostrar_inicio"
                    value="1"
                    @checked(
                        old(
                            'mostrar_inicio',
                            $promocion?->mostrar_inicio
                        )
                    )
                >

                <div>
                    <strong>
                        Mostrar en el inicio
                    </strong>

                    <span>
                        La promoción aparecerá destacada en la página principal.
                    </span>
                </div>

            </label>


            <label class="promotion-setting">

                <input
                    type="checkbox"
                    name="enviar_correo"
                    value="1"
                    @checked(
                        old(
                            'enviar_correo',
                            $promocion?->enviar_correo
                        )
                    )
                >

                <div>
                    <strong>
                        Avisar por correo
                    </strong>

                    <span>
                        Se notificará a los clientes que aceptaron promociones.
                    </span>
                </div>

            </label>

        </div>

    </div>

</div>