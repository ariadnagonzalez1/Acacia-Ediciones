<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Libro;
use App\Models\Promocion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromocionController extends Controller
{
    public function index()
    {
        $promociones = Promocion::query()
            ->with('libros')
            ->latest()
            ->get();

        return view('admin.promociones.index', compact(
            'promociones'
        ));
    }

    public function create()
    {
        $libros = Libro::query()
            ->where('estado', 'publicado')
            ->orderBy('titulo')
            ->get();

        return view('admin.promociones.create', compact(
            'libros'
        ));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
            ],

            'tipo' => [
                'required',
                'in:descuento,kit',
            ],

            'porcentaje_descuento' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'precio_kit' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'mensaje' => [
                'nullable',
                'string',
            ],

            'fecha_inicio' => [
                'nullable',
                'date',
            ],

            'fecha_fin' => [
                'nullable',
                'date',
                'after_or_equal:fecha_inicio',
            ],

            'libros' => [
                'required',
                'array',
                'min:1',
            ],

            'libros.*' => [
                'exists:libros,id',
            ],
        ]);

        if (
            $datos['tipo'] === 'descuento' &&
            empty($datos['porcentaje_descuento'])
        ) {
            return back()
                ->withErrors([
                    'porcentaje_descuento' =>
                        'Ingresá el porcentaje de descuento.'
                ])
                ->withInput();
        }

        if (
            $datos['tipo'] === 'kit' &&
            empty($datos['precio_kit'])
        ) {
            return back()
                ->withErrors([
                    'precio_kit' =>
                        'Ingresá el precio del kit.'
                ])
                ->withInput();
        }

        DB::transaction(function () use (
            $request,
            $datos
        ) {

            $promocion = Promocion::create([
                'nombre' => $datos['nombre'],
                'tipo' => $datos['tipo'],

                'porcentaje_descuento' =>
                    $datos['tipo'] === 'descuento'
                        ? $datos['porcentaje_descuento']
                        : null,

                'precio_kit' =>
                    $datos['tipo'] === 'kit'
                        ? $datos['precio_kit']
                        : null,

                'mensaje' =>
                    $datos['mensaje'] ?? null,

                'fecha_inicio' =>
                    $datos['fecha_inicio'] ?? null,

                'fecha_fin' =>
                    $datos['fecha_fin'] ?? null,

                'activa' =>
                    $request->boolean('activa'),

                'mostrar_inicio' =>
                    $request->boolean('mostrar_inicio'),

                'enviar_correo' =>
                    $request->boolean('enviar_correo'),
            ]);

            $promocion->libros()->sync(
                $datos['libros']
            );
        });

        return redirect()
            ->route('admin.promociones.index')
            ->with(
                'success',
                'Promoción creada correctamente.'
            );
    }

    public function edit(Promocion $promocion)
    {
        $promocion->load('libros');

        $libros = Libro::query()
            ->where('estado', 'publicado')
            ->orderBy('titulo')
            ->get();

        return view(
            'admin.promociones.edit',
            compact(
                'promocion',
                'libros'
            )
        );
    }

    public function update(
        Request $request,
        Promocion $promocion
    ) {
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
            ],

            'tipo' => [
                'required',
                'in:descuento,kit',
            ],

            'porcentaje_descuento' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'precio_kit' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'mensaje' => [
                'nullable',
                'string',
            ],

            'fecha_inicio' => [
                'nullable',
                'date',
            ],

            'fecha_fin' => [
                'nullable',
                'date',
                'after_or_equal:fecha_inicio',
            ],

            'libros' => [
                'required',
                'array',
                'min:1',
            ],

            'libros.*' => [
                'exists:libros,id',
            ],
        ]);

        DB::transaction(function () use (
            $request,
            $datos,
            $promocion
        ) {

            $promocion->update([
                'nombre' => $datos['nombre'],
                'tipo' => $datos['tipo'],

                'porcentaje_descuento' =>
                    $datos['tipo'] === 'descuento'
                        ? $datos['porcentaje_descuento']
                        : null,

                'precio_kit' =>
                    $datos['tipo'] === 'kit'
                        ? $datos['precio_kit']
                        : null,

                'mensaje' =>
                    $datos['mensaje'] ?? null,

                'fecha_inicio' =>
                    $datos['fecha_inicio'] ?? null,

                'fecha_fin' =>
                    $datos['fecha_fin'] ?? null,

                'activa' =>
                    $request->boolean('activa'),

                'mostrar_inicio' =>
                    $request->boolean('mostrar_inicio'),

                'enviar_correo' =>
                    $request->boolean('enviar_correo'),
            ]);

            $promocion->libros()->sync(
                $datos['libros']
            );
        });

        return redirect()
            ->route('admin.promociones.index')
            ->with(
                'success',
                'Promoción actualizada correctamente.'
            );
    }

    public function destroy(Promocion $promocion)
    {
        if ($promocion->ventaDetalles()->exists()) {

            $promocion->update([
                'activa' => false,
                'mostrar_inicio' => false,
            ]);

            return redirect()
                ->route('admin.promociones.index')
                ->with(
                    'success',
                    'La promoción tenía ventas asociadas, por eso fue desactivada en lugar de eliminarse.'
                );
        }

        $promocion->delete();

        return redirect()
            ->route('admin.promociones.index')
            ->with(
                'success',
                'Promoción eliminada correctamente.'
            );
    }
}