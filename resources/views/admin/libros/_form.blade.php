<div class="admin-form-grid">

    <div class="form-group">

        <label for="titulo">
            Título
        </label>

        <input
            type="text"
            id="titulo"
            name="titulo"
            value="{{ old('titulo', $libro?->titulo) }}"
            required
        >

        @error('titulo')
            <span class="form-error">
                {{ $message }}
            </span>
        @enderror

    </div>


    <div class="form-group">

        <label for="autor">
            Autor
        </label>

        <input
            type="text"
            id="autor"
            name="autor"
            value="{{ old('autor', $libro?->autor) }}"
            required
        >

        @error('autor')
            <span class="form-error">
                {{ $message }}
            </span>
        @enderror

    </div>


    <div class="form-group">

        <label for="categoria_id">
            Categoría
        </label>

        <select
            id="categoria_id"
            name="categoria_id"
            required
        >

            <option value="">
                Seleccionar categoría
            </option>

            @foreach($categorias as $categoria)

                <option
                    value="{{ $categoria->id }}"
                    @selected(
                        old('categoria_id', $libro?->categoria_id) == $categoria->id
                    )
                >
                    {{ $categoria->nombre }}
                </option>

            @endforeach

        </select>

        @error('categoria_id')
            <span class="form-error">
                {{ $message }}
            </span>
        @enderror

    </div>


    <div class="form-group">

        <label for="precio">
            Precio
        </label>

        <input
            type="number"
            id="precio"
            name="precio"
            min="0"
            step="0.01"
            value="{{ old('precio', $libro?->precio) }}"
            required
        >

        @error('precio')
            <span class="form-error">
                {{ $message }}
            </span>
        @enderror

    </div>


    <div class="form-group">

        <label for="paginas">
            Cantidad de páginas
        </label>

        <input
            type="number"
            id="paginas"
            name="paginas"
            min="1"
            value="{{ old('paginas', $libro?->paginas) }}"
        >

        @error('paginas')
            <span class="form-error">
                {{ $message }}
            </span>
        @enderror

    </div>


    <div class="form-group">

        <label for="anio_edicion">
            Año de edición
        </label>

        <input
            type="number"
            id="anio_edicion"
            name="anio_edicion"
            min="1000"
            max="9999"
            value="{{ old('anio_edicion', $libro?->anio_edicion) }}"
        >

        @error('anio_edicion')
            <span class="form-error">
                {{ $message }}
            </span>
        @enderror

    </div>


    <div class="form-group admin-form-full">

        <label for="informacion">
            Información
        </label>

        <textarea
            id="informacion"
            name="informacion"
            rows="5"
            placeholder="Descripción o información del libro"
        >{{ old('informacion', $libro?->informacion) }}</textarea>

        @error('informacion')
            <span class="form-error">
                {{ $message }}
            </span>
        @enderror

    </div>


    <div class="form-group admin-form-full">

        <label for="mensaje_correo">
            Mensaje que acompaña al ebook
        </label>

        <textarea
            id="mensaje_correo"
            name="mensaje_correo"
            rows="4"
            placeholder="Mensaje personalizado para el correo de entrega"
        >{{ old('mensaje_correo', $libro?->mensaje_correo) }}</textarea>

        @error('mensaje_correo')
            <span class="form-error">
                {{ $message }}
            </span>
        @enderror

    </div>


    <div class="form-group">

        <label for="estado">
            Estado
        </label>

        <select
            id="estado"
            name="estado"
            required
        >

            <option
                value="borrador"
                @selected(old('estado', $libro?->estado ?? 'borrador') === 'borrador')
            >
                Borrador
            </option>

            <option
                value="publicado"
                @selected(old('estado', $libro?->estado) === 'publicado')
            >
                Publicado
            </option>

        </select>

        @error('estado')
            <span class="form-error">
                {{ $message }}
            </span>
        @enderror

    </div>


    <div class="form-group">

        <label class="checkbox-label admin-checkbox">

            <input
                type="checkbox"
                name="destacado"
                value="1"
                @checked(old('destacado', $libro?->destacado))
            >

            <span>
                Mostrar como destacado
            </span>

        </label>

    </div>


    <div class="form-group">

        <label for="portada">
            Portada
        </label>

        <input
            type="file"
            id="portada"
            name="portada"
            accept=".jpg,.jpeg,.png,.webp"
            {{ $libro ? '' : 'required' }}
        >

        @if($libro?->portada)

            <div class="current-file-preview">

                <img
                    src="{{ asset('storage/' . $libro->portada) }}"
                    alt="{{ $libro->titulo }}"
                    class="current-book-cover"
                >

                <span>
                    Portada actual
                </span>

            </div>

        @endif

        @error('portada')
            <span class="form-error">
                {{ $message }}
            </span>
        @enderror

    </div>


    <div class="form-group">

        <label for="archivo_pdf">
            Archivo PDF
        </label>

        <input
            type="file"
            id="archivo_pdf"
            name="archivo_pdf"
            accept=".pdf"
            {{ $libro ? '' : 'required' }}
        >

        @if($libro?->archivo_pdf)

            <div class="current-file-info">
                PDF cargado actualmente
            </div>

        @endif

        @error('archivo_pdf')
            <span class="form-error">
                {{ $message }}
            </span>
        @enderror

    </div>

</div>