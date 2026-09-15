<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::query()
            ->withCount('libros')
            ->orderBy('nombre')
            ->get();

        return view('admin.categorias.index', compact(
            'categorias'
        ));
    }

    public function create()
    {
        return redirect()
            ->route('admin.categorias.index');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:100',
                'unique:categorias,nombre',
            ],

            'descripcion' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        Categoria::create($datos);

        return redirect()
            ->route('admin.categorias.index')
            ->with(
                'success',
                'Categoría creada correctamente.'
            );
    }

    public function edit(Categoria $categoria)
    {
        return view(
            'admin.categorias.edit',
            compact('categoria')
        );
    }

    public function update(
        Request $request,
        Categoria $categoria
    ) {
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:100',

                Rule::unique(
                    'categorias',
                    'nombre'
                )->ignore($categoria->id),
            ],

            'descripcion' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $categoria->update($datos);

        return redirect()
            ->route('admin.categorias.index')
            ->with(
                'success',
                'Categoría actualizada correctamente.'
            );
    }

    public function destroy(Categoria $categoria)
    {
        if ($categoria->libros()->exists()) {
            return back()->with(
                'error',
                'No podés eliminar una categoría que contiene libros.'
            );
        }

        $categoria->delete();

        return redirect()
            ->route('admin.categorias.index')
            ->with(
                'success',
                'Categoría eliminada correctamente.'
            );
    }
}