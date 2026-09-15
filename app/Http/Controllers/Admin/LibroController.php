<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LibroController extends Controller
{
    public function index()
    {
        $libros = Libro::query()
            ->with('categoria')
            ->latest()
            ->paginate(20);

        return view('admin.libros.index', compact(
            'libros'
        ));
    }

    public function create()
    {
        $categorias = Categoria::query()
            ->orderBy('nombre')
            ->get();

        return view('admin.libros.create', compact(
            'categorias'
        ));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'titulo' => [
                'required',
                'string',
                'max:255',
            ],

            'autor' => [
                'required',
                'string',
                'max:255',
            ],

            'categoria_id' => [
                'required',
                'exists:categorias,id',
            ],

            'precio' => [
                'required',
                'numeric',
                'min:0',
            ],

            'paginas' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'anio_edicion' => [
                'nullable',
                'integer',
                'min:1000',
                'max:9999',
            ],

            'informacion' => [
                'nullable',
                'string',
            ],

            'mensaje_correo' => [
                'nullable',
                'string',
            ],

            'estado' => [
                'required',
                'in:borrador,publicado',
            ],

            'destacado' => [
                'nullable',
                'boolean',
            ],

            'archivo_pdf' => [
                'required',
                'file',
                'mimes:pdf',
                'max:51200',
            ],

            'portada' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        $datos['archivo_pdf'] = $request
            ->file('archivo_pdf')
            ->store('libros', 'public');

        $datos['portada'] = $request
            ->file('portada')
            ->store('portadas', 'public');

        $datos['destacado'] = $request->boolean(
            'destacado'
        );

        Libro::create($datos);

        return redirect()
            ->route('admin.libros.index')
            ->with(
                'success',
                'Libro guardado correctamente.'
            );
    }

    public function edit(Libro $libro)
    {
        $categorias = Categoria::query()
            ->orderBy('nombre')
            ->get();

        return view('admin.libros.edit', compact(
            'libro',
            'categorias'
        ));
    }

    public function update(
        Request $request,
        Libro $libro
    ) {
        $datos = $request->validate([
            'titulo' => [
                'required',
                'string',
                'max:255',
            ],

            'autor' => [
                'required',
                'string',
                'max:255',
            ],

            'categoria_id' => [
                'required',
                'exists:categorias,id',
            ],

            'precio' => [
                'required',
                'numeric',
                'min:0',
            ],

            'paginas' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'anio_edicion' => [
                'nullable',
                'integer',
                'min:1000',
                'max:9999',
            ],

            'informacion' => [
                'nullable',
                'string',
            ],

            'mensaje_correo' => [
                'nullable',
                'string',
            ],

            'estado' => [
                'required',
                'in:borrador,publicado',
            ],

            'destacado' => [
                'nullable',
                'boolean',
            ],

            'archivo_pdf' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:51200',
            ],

            'portada' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        if ($request->hasFile('archivo_pdf')) {

            if ($libro->archivo_pdf) {
                Storage::disk('public')->delete(
                    $libro->archivo_pdf
                );
            }

            $datos['archivo_pdf'] = $request
                ->file('archivo_pdf')
                ->store('libros', 'public');
        }

        if ($request->hasFile('portada')) {

            if ($libro->portada) {
                Storage::disk('public')->delete(
                    $libro->portada
                );
            }

            $datos['portada'] = $request
                ->file('portada')
                ->store('portadas', 'public');
        }

        $datos['destacado'] = $request->boolean(
            'destacado'
        );

        $libro->update($datos);

        return redirect()
            ->route('admin.libros.index')
            ->with(
                'success',
                'Libro actualizado correctamente.'
            );
    }

    public function destroy(Libro $libro)
    {
        if ($libro->ventaDetalles()->exists()) {
            return back()->with(
                'error',
                'No se puede eliminar un libro que ya tiene ventas registradas.'
            );
        }

        if ($libro->archivo_pdf) {
            Storage::disk('public')->delete(
                $libro->archivo_pdf
            );
        }

        if ($libro->portada) {
            Storage::disk('public')->delete(
                $libro->portada
            );
        }

        $libro->delete();

        return redirect()
            ->route('admin.libros.index')
            ->with(
                'success',
                'Libro eliminado correctamente.'
            );
    }
}