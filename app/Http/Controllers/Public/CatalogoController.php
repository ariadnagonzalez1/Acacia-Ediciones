<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Libro;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        $libros = Libro::query()
            ->with('categoria')
            ->where('estado', 'publicado')

            ->when($request->filled('categoria'), function ($query) use ($request) {
                $query->where('categoria_id', $request->categoria);
            })

            ->when($request->filled('buscar'), function ($query) use ($request) {

                $buscar = trim($request->buscar);

                $query->where(function ($subquery) use ($buscar) {
                    $subquery
                        ->where('titulo', 'like', "%{$buscar}%")
                        ->orWhere('autor', 'like', "%{$buscar}%");
                });
            })

            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categorias = Categoria::query()
            ->orderBy('nombre')
            ->get();

        return view('public.catalogo.index', compact(
            'libros',
            'categorias'
        ));
    }

    public function show(Libro $libro)
{
    abort_unless(
        $libro->estado === 'publicado',
        404
    );

    $libro->load('categoria');

    $recomendados = Libro::query()
        ->where('estado', 'publicado')
        ->where('id', '!=', $libro->id)
        ->where(function ($query) use ($libro) {

            $query
                ->where('categoria_id', $libro->categoria_id)
                ->orWhere('autor', $libro->autor);

        })
        ->with('categoria')
        ->limit(4)
        ->get();

    return view(
        'public.libros.show',
        compact(
            'libro',
            'recomendados'
        )
    );
}
}