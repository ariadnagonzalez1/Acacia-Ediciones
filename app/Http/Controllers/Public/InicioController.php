<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Libro;
use App\Models\Promocion;

class InicioController extends Controller
{
    public function index()
    {
        $librosDestacados = Libro::query()
            ->with('categoria')
            ->where('estado', 'publicado')
            ->where('destacado', true)
            ->latest()
            ->limit(8)
            ->get();

        $promociones = Promocion::query()
            ->with('libros.categoria')
            ->where('activa', true)
            ->where('mostrar_inicio', true)
            ->where(function ($query) {
                $query->whereNull('fecha_inicio')
                    ->orWhereDate('fecha_inicio', '<=', today());
            })
            ->where(function ($query) {
                $query->whereNull('fecha_fin')
                    ->orWhereDate('fecha_fin', '>=', today());
            })
            ->get();

        return view('public.inicio.index', compact(
            'librosDestacados',
            'promociones'
        ));
    }

    public function showKit(Promocion $promocion)
{
    abort_unless(
        $promocion->activa
        && $promocion->tipo === 'kit',
        404
    );

    $promocion->load([
        'libros.categoria'
    ]);

    return view(
        'public.kits.show',
        compact('promocion')
    );
}
}